// Visor 3D básico de fallback
class Simple3DViewer {
    constructor(containerId, productType, parameters) {
        this.containerId = containerId;
        this.container = document.getElementById(containerId);
        this.productType = productType;
        this.parameters = parameters;
        
        if (!this.container) {
            return;
        }

        this.init();
    }

    init() {
        try {
            // Verificar Three.js global
            if (typeof window.THREE === 'undefined') {
                throw new Error('Three.js no está disponible en window.THREE');
            }
            
            const THREE = window.THREE;
            this.THREE = THREE;
            this.scene = new THREE.Scene();
            this.camera = new THREE.PerspectiveCamera(75, this.container.clientWidth / this.container.clientHeight, 0.1, 1000);
            this.renderer = new THREE.WebGLRenderer({ antialias: true });
            
            this.renderer.setSize(this.container.clientWidth, this.container.clientHeight);
            this.renderer.setClearColor(0xf0f0f0);
            
            // Limpiar contenedor y agregar canvas
            this.container.innerHTML = '';
            this.container.appendChild(this.renderer.domElement);

            // Agregar iluminación
            const ambientLight = new THREE.AmbientLight(0x404040, 0.6);
            this.scene.add(ambientLight);

            const directionalLight = new THREE.DirectionalLight(0xffffff, 0.8);
            directionalLight.position.set(10, 10, 5);
            this.scene.add(directionalLight);

            // Generar modelo según tipo
            this.generateModel();

            // Posicionar cámara
            this.camera.position.set(5, 5, 5);
            this.camera.lookAt(0, 0, 0);

            // Iniciar renderizado
            this.animate();

        } catch (error) {
            this.showError(error.message);
        }
    }

    generateModel() {
        const THREE = this.THREE;
        const { width, height, depth, color } = this.parameters;

        let geometry, material, mesh;

        switch (this.productType) {
            case 'window':
                // Crear una ventana simple
                geometry = new THREE.BoxGeometry(width, height, depth);
                material = new THREE.MeshLambertMaterial({ 
                    color: color || '#8B4513',
                    transparent: true,
                    opacity: 0.8
                });
                mesh = new THREE.Mesh(geometry, material);

                // Agregar "vidrio"
                const glassGeometry = new THREE.PlaneGeometry(width * 0.8, height * 0.8);
                const glassMaterial = new THREE.MeshLambertMaterial({ 
                    color: '#87CEEB',
                    transparent: true,
                    opacity: 0.3
                });
                const glass = new THREE.Mesh(glassGeometry, glassMaterial);
                glass.position.z = depth * 0.1;
                
                this.scene.add(mesh);
                this.scene.add(glass);
                break;

            case 'door':
                // Crear una puerta simple
                geometry = new THREE.BoxGeometry(width, height, depth);
                material = new THREE.MeshLambertMaterial({ color: color || '#8B4513' });
                mesh = new THREE.Mesh(geometry, material);

                // Agregar manija
                const handleGeometry = new THREE.CylinderGeometry(0.02, 0.02, 0.1);
                const handleMaterial = new THREE.MeshLambertMaterial({ color: '#FFD700' });
                const handle = new THREE.Mesh(handleGeometry, handleMaterial);
                handle.position.set(width * 0.3, 0, depth * 0.6);
                handle.rotation.z = Math.PI / 2;

                this.scene.add(mesh);
                this.scene.add(handle);
                break;

            default:
                // Mueble genérico
                geometry = new THREE.BoxGeometry(width, height, depth);
                material = new THREE.MeshLambertMaterial({ color: color || '#8B4513' });
                mesh = new THREE.Mesh(geometry, material);
                this.scene.add(mesh);
        }

        this.currentModel = mesh;
    }

    updateParameters(newParams) {
        this.parameters = { ...this.parameters, ...newParams };
        
        // Limpiar escena
        while(this.scene.children.length > 2) { // Mantener las luces
            this.scene.remove(this.scene.children[2]);
        }
        
        // Regenerar modelo
        this.generateModel();
    }

    animate() {
        requestAnimationFrame(() => this.animate());
        
        // Rotar modelo lentamente
        if (this.currentModel) {
            this.currentModel.rotation.y += 0.005;
        }
        
        this.renderer.render(this.scene, this.camera);
    }

    showError(message) {
        this.container.innerHTML = `
            <div class="flex items-center justify-center h-full text-red-600">
                <div class="text-center p-4">
                    <svg class="mx-auto h-12 w-12 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <h3 class="text-lg font-medium mb-2">Error del Visor 3D</h3>
                    <p class="text-sm">${message}</p>
                    <p class="text-xs mt-2 text-gray-500">Revisa la consola para más detalles</p>
                </div>
            </div>
        `;
    }
}

// Función de fallback
window.createSimple3DViewer = function(containerId, productType, parameters) {
    return new Simple3DViewer(containerId, productType, parameters);
};

// Si la función principal no existe, usar la simple
if (typeof window.createParametricProduct3D === 'undefined') {
    window.createParametricProduct3D = window.createSimple3DViewer;
}

export { Simple3DViewer };