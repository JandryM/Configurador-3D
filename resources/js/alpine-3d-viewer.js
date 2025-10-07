// Componente Alpine.js para el visor 3D
document.addEventListener('alpine:init', () => {
    Alpine.data('model3DViewer', (config) => ({
        // Estado reactivo
        loading: true,
        error: null,
        viewer: null,
        settings: {
            backgroundColor: config.backgroundColor || '#f0f0f0',
            showWireframe: false,
            enableShadows: true,
            scale: config.scale || 1.0,
            ...config.settings
        },

        // Inicialización
        init() {
            this.initViewer();
            this.loadModel(config.modelUrl);
        },

        // Crear visor 3D usando el sistema paramétrico
        initViewer() {
            try {
                // Usar el configurador paramétrico en lugar del visor estático
                this.viewer = window.createParametricProduct3D(
                    this.$refs.container.id, 
                    config.productType || 'window',
                    {
                        ...this.settings,
                        width: config.parameters?.width || 1.0,
                        height: config.parameters?.height || 1.0,
                        depth: config.parameters?.depth || 0.1,
                        frameWidth: config.parameters?.frameWidth || 0.05,
                        color: config.parameters?.color || '#8B4513'
                    }
                );
            } catch (error) {
                this.error = 'Error inicializando visor 3D';
                console.error(error);
            }
        },

        // El modelo se genera automáticamente, no necesita carga
        async loadModel(url) {
            // Con el sistema paramétrico, el modelo se genera automáticamente
            this.loading = false;
        },

        // Métodos reactivos
        toggleWireframe() {
            this.settings.showWireframe = !this.settings.showWireframe;
            if (this.viewer) {
                this.viewer.toggleWireframe(this.settings.showWireframe);
            }
        },

        updateBackground() {
            if (this.viewer) {
                this.viewer.setBackgroundColor(this.settings.backgroundColor);
            }
        },

        updateScale() {
            if (this.viewer && this.viewer.productMesh) {
                this.viewer.productMesh.scale.setScalar(this.settings.scale);
                this.viewer.fitCameraToProduct();
            }
        },

        resetCamera() {
            if (this.viewer && this.viewer.controls) {
                this.viewer.controls.reset();
                this.viewer.fitCameraToProduct();
            }
        },

        takeScreenshot() {
            if (this.viewer) {
                const dataURL = this.viewer.screenshot(800, 600);
                const link = document.createElement('a');
                link.download = `model_3d_screenshot_${Date.now()}.png`;
                link.href = dataURL;
                link.click();
            }
        },

        // Cleanup
        destroy() {
            if (this.viewer) {
                this.viewer.dispose();
            }
        }
    }));
});

export default {};