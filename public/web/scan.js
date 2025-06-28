document.addEventListener("DOMContentLoaded", async () => {
    const video = document.getElementById("videoInput");
    const guide = document.getElementById("guideCanvas");
    const statusMsg = document.getElementById("statusMessage");
    const loading = document.getElementById("loadingIndicator");
    const beep = document.getElementById("beepSound");
    const notFound = document.getElementById("notFoundMessage");
    const container = document.getElementById("recognizedFaceContainer");
    const startBtn = document.getElementById("startScanButton");
    const containerDiv = document.querySelector(".container");
    const watermarkUrl = document.body.dataset.watermark;

    let faceData = [],
        descriptors = [],
        interval;
    let captureStep = 0;
    let modelsLoaded = false;
    let referenceLoaded = false;

    const steps = [
        { label: "TENGAH", audio: "voiceCenter", box: [0.25, 0.2, 0.5, 0.6] },
        { label: "KIRI", audio: "voiceLeft", box: [0.0, 0.2, 0.5, 0.6] },
        { label: "KANAN", audio: "voiceRight", box: [0.5, 0.2, 0.5, 0.6] },
    ];

    const detectorOptions = new faceapi.TinyFaceDetectorOptions({
        inputSize: 512,
        scoreThreshold: 0.4,
    });

    // 👇 Muat model saat halaman selesai dimuat
    async function loadModels() {
        try {
            statusMsg.innerText = "⏳ Memuat model...";
            loading.style.display = "block";

            await Promise.all([
                faceapi.nets.tinyFaceDetector.loadFromUri("/models"),
                faceapi.nets.faceLandmark68TinyNet.loadFromUri("/models"),
                faceapi.nets.faceRecognitionNet.loadFromUri("/models"),
            ]);
            modelsLoaded = true;
            statusMsg.innerText = "✅ Model berhasil dimuat.";
        } catch (error) {
            statusMsg.innerText = "❌ Gagal memuat model";
            console.error("Error loading models:", error);
        } finally {
            loading.style.display = "none";
        }
    }

    await loadModels(); // panggil saat DOM ready

    // 👇 Tombol mulai scan
    startBtn.addEventListener("click", async () => {
        if (!modelsLoaded) {
            alert("Model belum siap. Silakan tunggu sebentar...");
            return;
        }

        startBtn.style.display = "none";
        statusMsg.innerText = "⏳ Memuat data wajah...";
        loading.style.display = "block";

        if (!referenceLoaded) {
            await loadReferenceImages();
            referenceLoaded = true;
        }

        await startCamera();
        loading.style.display = "none";
    });

    async function loadReferenceImages() {
        const res = await fetch("/api/label-images");
        const imgMap = await res.json();
        const tasks = [];

        Object.entries(imgMap).forEach(([label, imageList]) => {
            imageList.forEach((img) => {
                tasks.push(
                    processImage(label.toLowerCase(), img.url, img.slug)
                );
            });
        });

        const results = await Promise.all(tasks);
        results.filter(Boolean).forEach(({ label, descriptor, url, slug }) => {
            let entry = faceData.find((f) => f.label === label);
            if (entry) {
                entry.descriptors.push(descriptor);
                entry.images.push({ url, slug });
            } else {
                faceData.push({
                    label,
                    descriptors: [descriptor],
                    images: [{ url, slug }],
                });
            }
        });
    }

    async function processImage(label, url, slug) {
        try {
            const img = await faceapi.fetchImage(url);
            const det = await faceapi
                .detectSingleFace(img, detectorOptions)
                .withFaceLandmarks({ useTinyLandmarkNet: true })
                .withFaceDescriptor();
            if (!det) return null;
            return { label, descriptor: det.descriptor, url, slug };
        } catch (err) {
            console.error("Gagal memproses gambar:", url, err);
            return null;
        }
    }

    async function startCamera() {
        try {
            const stream = await navigator.mediaDevices.getUserMedia({
                video: true,
            });
            video.srcObject = stream;
            containerDiv.style.display = "block";

            video.addEventListener("loadedmetadata", () => {
                guide.width = video.videoWidth;
                guide.height = video.videoHeight;
                scanStep();
            });

            statusMsg.innerText = "📷 Kamera aktif, silakan ikuti panduan...";
        } catch {
            alert("❌ Mohon izinkan akses kamera.");
        }
    }

    function drawGuide(rect) {
        const ctx = guide.getContext("2d");
        ctx.clearRect(0, 0, guide.width, guide.height);
        ctx.strokeStyle = "#00ff88";
        ctx.lineWidth = 3;
        const [x, y, w, h] = rect;
        ctx.strokeRect(
            x * guide.width,
            y * guide.height,
            w * guide.width,
            h * guide.height
        );
    }

    function scanStep() {
        const step = steps[captureStep];
        drawGuide(step.box);
        statusMsg.innerText = `📷 Arahkan wajah ke posisi ${step.label}`;
        let guided = false;

        interval = setInterval(async () => {
            const det = await faceapi
                .detectSingleFace(video, detectorOptions)
                .withFaceLandmarks({ useTinyLandmarkNet: true })
                .withFaceDescriptor();
            if (det) {
                const box = det.detection.box;
                const [gx, gy, gw, gh] = step.box;
                const inBox =
                    box.x > gx * guide.width &&
                    box.y > gy * guide.height &&
                    box.x + box.width < (gx + gw) * guide.width &&
                    box.y + box.height < (gy + gh) * guide.height;

                if (inBox && !guided) {
                    guided = true;
                    statusMsg.innerText = `✅ Posisi ${step.label} sesuai, harap diam...`;
                    document.getElementById(step.audio)?.play();

                    setTimeout(() => {
                        descriptors.push(det.descriptor);
                        beep.play();
                        clearInterval(interval);
                        captureStep++;
                        if (captureStep < steps.length) {
                            setTimeout(scanStep, 1000);
                        } else {
                            finalizeScan();
                        }
                    }, 1500);
                }
            }
        }, 400);
    }

    function finalizeScan() {
        statusMsg.innerText = "🔍 Mencocokkan wajah...";
        loading.style.display = "block";
        stopCamera();

        const avgDescriptor = averageDescriptors(descriptors);
        container.innerHTML = "";
        let foundMatch = false;

        faceData.forEach((entry) => {
            entry.descriptors.forEach((d, i) => {
                const dist = faceapi.euclideanDistance(avgDescriptor, d);

                if (dist < 0.55) {
                    const similarity = Math.max(0, 1 - dist / 0.55) * 100;
                    if (similarity < 10) return;

                    foundMatch = true;
                    const img = entry.images[i];

                    const wrapper = document.createElement("div");
                    wrapper.className = "face-wrapper";
                    wrapper.style.position = "relative";
                    wrapper.style.display = "flex";
                    wrapper.style.flexDirection = "column";
                    wrapper.style.alignItems = "center";
                    wrapper.style.marginBottom = "20px";

                    const link = document.createElement("a");
                    link.href = `/keranjang/${img.slug}`;
                    link.target = "_blank";
                    link.className = "matched-face";
                    link.style.pointerEvents = "none";

                    const card = document.createElement("div");
                    card.className = "face-card";
                    card.innerHTML = `
                        <div class="image-wrapper">
                          <div class="watermark-bg" style="background-image: url('${watermarkUrl}')"></div>
                          <img src="${img.url}" class="original">
                        </div>
                        <div class="similarity-score">✅ Kemiripan: ${similarity.toFixed(
                            1
                        )}%</div>
                    `;
                    link.appendChild(card);

                    const options = document.createElement("div");
                    options.className = "face-options";
                    options.innerHTML = `
                        <button class="yes-btn">✔ Ini saya</button>
                        <button class="no-btn">✖ Bukan saya</button>
                    `;
                    options.style.marginTop = "10px";
                    options.style.textAlign = "center";
                    options.style.display = "flex";
                    options.style.gap = "12px";

                    wrapper.appendChild(link);
                    wrapper.appendChild(options);
                    container.appendChild(wrapper);

                    options
                        .querySelector(".no-btn")
                        .addEventListener("click", () => {
                            wrapper.remove();
                        });

                    options
                        .querySelector(".yes-btn")
                        .addEventListener("click", () => {
                            const scoreDiv =
                                card.querySelector(".similarity-score");
                            scoreDiv.innerHTML = "✅ Dikonfirmasi";
                            link.style.pointerEvents = "auto";
                            options.remove();
                            link.classList.add("confirmed");
                        });
                }
            });
        });

        loading.style.display = "none";
        if (foundMatch) {
            statusMsg.innerText = "✅ Wajah dikenali";
            notFound.style.display = "none";
        } else {
            statusMsg.innerText = "❌ Wajah tidak ditemukan";
            notFound.style.display = "block";
        }
    }

    function averageDescriptors(list) {
        const avg = new Float32Array(128);
        list.forEach((desc) => {
            for (let i = 0; i < 128; i++) {
                avg[i] += desc[i];
            }
        });
        for (let i = 0; i < 128; i++) {
            avg[i] /= list.length;
        }
        return avg;
    }

    function stopCamera() {
        const stream = video.srcObject;
        if (stream) {
            stream.getTracks().forEach((track) => track.stop());
            video.srcObject = null;
        }
    }
});

// // Blok klik kanan
// document.addEventListener("contextmenu", (e) => e.preventDefault());

// // Blok shortcut keyboard
// document.addEventListener("keydown", function (e) {
//     const blockedKeys = ["F12", "F11", "F10", "F9", "PrintScreen"];
//     if (
//         blockedKeys.includes(e.key) ||
//         e.keyCode === 44 || // PrintScreen
//         e.keyCode === 91 || // Windows Key
//         (e.ctrlKey && ["u", "s", "p"].includes(e.key.toLowerCase())) ||
//         (e.ctrlKey &&
//             e.shiftKey &&
//             ["i", "c", "j"].includes(e.key.toLowerCase()))
//     ) {
//         e.preventDefault();
//         alert("Akses dibatasi demi keamanan.");
//     }
// });

// // Coba mendeteksi PrintScreen (tidak pasti berhasil)
// document.addEventListener("keyup", function (e) {
//     if (e.key === "PrintScreen") {
//         alert("Screenshot tidak diperbolehkan!");
//     }
// });

// // Cegah long press di mobile
// document.addEventListener("touchstart", preventLongPress, false);
// document.addEventListener("touchend", preventLongPress, false);
// let timer;
// function preventLongPress(e) {
//     if (e.type === "touchstart") {
//         timer = setTimeout(() => {
//             e.preventDefault();
//         }, 500);
//     } else {
//         clearTimeout(timer);
//     }
// }
