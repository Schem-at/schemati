<div class="aspect-w-4 aspect-h-3 w-full min-h-96 relative justify-center items-center flex">
    <canvas id="canvas-{{ $schematicId }}" wire:ignore class="w-full h-[50vh]">
        Your browser does not support the HTML5 canvas tag.
    </canvas>
    <div class="absolute top-0 left-0 w-full h-full flex items-center justify-center pointer-events-none"
        style="display: none" id="progress-{{ $schematicId }}">
        <div class="w-full bg-gray-200 rounded-full dark:bg-gray-700 max-w-[300px] relative">
            <div class="bg-primary text-xs font-medium text-white-100 text-center p-0.5 leading-none rounded-full progress-value min-h-6"
                style="width: 0%"></div>
            <div class="absolute inset-0 flex items-center justify-center progress-message">
                <div class="text-xs font-medium text-gray-700 dark:text-gray-300"></div>
            </div>

        </div>
    </div>
</div>


@assets
    <script src="http://localhost/js/bundle.js"></script>
@endassets

@script
    <script>
        console.log('Schematic renderer script loaded');
        async function openDatabase() {
            return new Promise((resolve, reject) => {
                const request = indexedDB.open("minecraftDB", 1);
                request.onupgradeneeded = function(event) {
                    const db = event?.target?.result;
                    if (!db.objectStoreNames.contains("jars")) {
                        db.createObjectStore("jars");
                    }
                };
                request.onsuccess = function(event) {
                    resolve(event?.target?.result);
                };
                request.onerror = function(event) {
                    reject("Error opening IndexedDB.");
                };
            });
        }

        function base64ToUint8Array(base64) {
            const binaryString = atob(base64);
            const len = binaryString.length;
            const bytes = new Uint8Array(len);
            for (let i = 0; i < len; i++) {
                bytes[i] = binaryString.charCodeAt(i);
            }
            return bytes;
        }

        async function getCachedMinecraftJarUrl() {
            const jarURL = "public/jars/client.jar";
            const jarUrlHash = "c0898ec7c6a5a2eaa317770203a1554260699994";
            const db = await openDatabase();
            const transaction = db.transaction(["jars"], "readonly");
            const objectStore = transaction.objectStore("jars");
            const request = objectStore.get(jarUrlHash);
            return new Promise(async (resolve, reject) => {
                request.onsuccess = function(event) {
                    if (request.result) {
                        console.log("Jar found in IndexedDB.");
                        resolve(URL.createObjectURL(request.result));
                    } else {
                        console.log(
                            "Jar not found in IndexedDB, fetching from Mojang..."
                        );
                        fetch(jarURL)
                            .then((response) => {
                                if (!response.ok) {
                                    throw new Error("HTTP error " + response.status);
                                }
                                console.log("Jar fetched from Mojang, unzipping...");
                                const blob = response.blob();
                                console.log(blob);
                                return blob;
                            })
                            .then((blob) => {
                                console.log(
                                    "Jar fetched from Mojang, storing in IndexedDB..."
                                );
                                return blob;
                            })
                            .then((blob) => {
                                const addRequest = db
                                    .transaction(["jars"], "readwrite")
                                    .objectStore("jars")
                                    .add(blob, jarUrlHash);

                                addRequest.onsuccess = function(event) {
                                    resolve(URL.createObjectURL(blob));
                                };
                                addRequest.onerror = function(event) {
                                    reject("Error storing jar in IndexedDB.");
                                };
                            })
                            .catch((error) => {
                                reject("Error fetching jar from Mojang.");
                            });
                    }
                };
                request.onerror = function(event) {
                    reject("Error fetching jar from IndexedDB.");
                };
            });
        }
        const defaultSchematicOptions = {
            getClientJarUrl: async (props) => {
                return await getCachedMinecraftJarUrl();
            },
        };

        function setCanvasDimensions(canvasId) {
            const canvas = document.getElementById(canvasId);
            const container = canvas.parentElement;
            canvas.width = container.offsetWidth;
            canvas.height = container.offsetHeight;
        }

        function showProgress(progressId) {
            const progress = document.getElementById(progressId);
            progress.style = '';
        }

        function hideProgress(progressId) {
            const progress = document.getElementById(progressId);
            progress.style = 'display: none';
        }

        function toggleProgress(progressId) {
            const progress = document.getElementById(progressId);
            if (progress.style.display === 'none') {
                showProgress(progressId);
            } else {
                hideProgress(progressId);
            }
        }

        function setProgress(progressId, progress) {
            const progressElement = document.querySelector(`#${progressId} > div > .progress-value`);
            progressElement.style.width = `${progress}%`;
        }

        function setProgressMessage(progressId, text) {
            const progressElement = document.querySelector(`#${progressId} > div > .progress-message > div`);
            progressElement.textContent = text;
        }

        console.log('Schematic renderer script loaded');
        setCanvasDimensions('canvas-{{ $schematicId }}');
        window.addEventListener('resize', () => setCanvasDimensions('canvas-{{ $schematicId }}'));
        const schematic_{{ $schematicId }} = @json($schematicBase64);
        const canvas_{{ $schematicId }} = document.getElementById('canvas-{{ $schematicId }}');
        const options = {
            ...defaultSchematicOptions,
            progressController: {
                showProgress: async () => showProgress('progress-{{ $schematicId }}'),
                hideProgress: async () => hideProgress('progress-{{ $schematicId }}'),
                setProgress: async (progress) => setProgress('progress-{{ $schematicId }}', progress),
                setProgressMessage: async (text) => setProgressMessage('progress-{{ $schematicId }}', text)
            }
        }
        const renderer_{{ $schematicId }} = new SchematicRenderer.SchematicRenderer(
            canvas_{{ $schematicId }},
            schematic_{{ $schematicId }},
            options
        );


        function generatePreview() {
            const webmInput = document.getElementById('schematicWebMPreview');
            if (!webmInput) {
                console.error('No webm input found');
                return;
            }
            const pngInput = document.getElementById('schematicPngPreview');
            if (!pngInput) {
                console.error('No png input found');
                return;
            }
            const resolutionX = 720
            const resolutionY = 480;
            const frameRate = 24;
            const duration = 5;
            const rotation = 360;
            console.log('Generating preview');
            //store the webm and png in the inputs
            renderer_{{ $schematicId }}.takeRotationWebM(resolutionX, resolutionY, frameRate, duration, rotation)
                .then(
                    webm => {
                        webmInput.value = webm;
                        return renderer_{{ $schematicId }}.takeScreenshot(resolutionX, resolutionY)
                            .then(
                                png => {
                                    pngInput.value = png;
                                }
                            )
                    });
        }
    </script>
@endscript
