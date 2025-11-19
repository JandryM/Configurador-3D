// Modelo 3D para Malla Antimosquitos
export class MeshModel {
	constructor(THREE) {
		this.THREE = THREE;
		this.textureCache = new Map();
	}

	generate(parameters) {
		const group = new this.THREE.Group();
		const {
			width = 1.0,
			height = 1.2,
			depth = 0.02,
			frameWidth = 0.03,
			texturePath = '/textures/aluminum/natural/',
			frameColor = 0xC0C0C0
		} = parameters;

		// Materiales con texturas del sistema de ventanas
		const frameMaterial = this.createAluminumMaterial(texturePath, frameColor);
		const meshMaterial = new this.THREE.MeshPhysicalMaterial({
			color: 0x333333,
			metalness: 0.3,
			roughness: 0.6,
			transparent: true,
			opacity: 0.3,
			wireframe: true,
			wireframeLinewidth: 1
		});

		// Marco exterior (4 lados)
		const frameGeometries = [
			// Superior
			[width, frameWidth, depth],
			// Inferior
			[width, frameWidth, depth],
			// Izquierda
			[frameWidth, height - 2 * frameWidth, depth],
			// Derecha
			[frameWidth, height - 2 * frameWidth, depth]
		];
		const framePositions = [
			[0, (height - frameWidth) / 2, 0],
			[0, -(height - frameWidth) / 2, 0],
			[-(width - frameWidth) / 2, 0, 0],
			[(width - frameWidth) / 2, 0, 0]
		];
		for (let i = 0; i < 4; i++) {
			const geo = new this.THREE.BoxGeometry(...frameGeometries[i]);
			const mesh = new this.THREE.Mesh(geo, frameMaterial);
			mesh.position.set(...framePositions[i]);
			group.add(mesh);
		}

		// Malla central
		const meshWidth = width - 2 * frameWidth;
		const meshHeight = height - 2 * frameWidth;
		// Aumentar la segmentación para cuadros más pequeños
		const meshGeo = new this.THREE.PlaneGeometry(meshWidth, meshHeight, 60, 60);
		// Simular patrón de malla con wireframe
		const meshMesh = new this.THREE.Mesh(meshGeo, meshMaterial);
		meshMesh.position.z = 0.001; // Ligeramente al frente
		group.add(meshMesh);

		// Sombra
		group.traverse(child => {
			if (child.isMesh) {
				child.castShadow = true;
				child.receiveShadow = true;
			}
		});

		// Almacenar referencia al modelo y función de actualización
		this.model = group;
		group.userData.updateColors = (newParameters) => {
			this.updateColors(newParameters);
		};

		return group;
	}

	// Crear material de aluminio con texturas PBR (igual que WindowModel)
	createAluminumMaterial(texturePath, fallbackColor = 0xC0C0C0) {
		// Lógica especial para color blanco (debe ir ANTES de verificar cache)
		let useWhiteTexture = false;
		if (texturePath.toLowerCase().includes('white')) {
			texturePath = '/textures/aluminum/white/';
			useWhiteTexture = true;
		}

		// Usar cache para evitar cargar texturas repetidas
		if (this.textureCache.has(texturePath)) {
			const cached = this.textureCache.get(texturePath);
			const materialConfig = {
				metalness: 0.8,
				roughness: 0.4,
				envMapIntensity: 0.3,
				clearcoat: 0.1,
				clearcoatRoughness: 0.2,
				opacity: 1.0,
				transparent: false
			};

			if (cached.baseColor) materialConfig.map = cached.baseColor;
			if (cached.metalness) {
				materialConfig.normalMap = cached.metalness;
				materialConfig.normalScale = new this.THREE.Vector2(0.3, 0.3);
			}
			if (cached.roughness) {
				materialConfig.roughnessMap = cached.roughness;
				materialConfig.aoMap = cached.roughness;
				materialConfig.aoMapIntensity = 0.2;
			}
			if (cached.displacement) {
				materialConfig.displacementMap = cached.displacement;
				materialConfig.displacementScale = 0.005;
				materialConfig.displacementBias = -0.002;
			}
			if (useWhiteTexture) {
				materialConfig.color = new this.THREE.Color('#F8F8FF');
				materialConfig.metalness = 0.4;
			}

			return new this.THREE.MeshPhysicalMaterial(materialConfig);
		}

		// Cargar texturas del aluminio (mismos nombres que WindowModel)
		const textureLoader = new this.THREE.TextureLoader();
		let baseColor, roughness, normal, metalness, displacement;

		try {
			baseColor = textureLoader.load(`${texturePath}Metal050A_1K-JPG_Color.jpg`);
			roughness = textureLoader.load(`${texturePath}Metal050A_1K-JPG_Roughness.jpg`);
			normal = textureLoader.load(`${texturePath}Metal050A_1K-JPG_NormalGL.jpg`);
			metalness = textureLoader.load(`${texturePath}Metal050A_1K-JPG_Metalness.jpg`);
			displacement = textureLoader.load(`${texturePath}Metal050A_1K-JPG_Displacement.jpg`);

			// Configurar texturas (igual que WindowModel)
			[baseColor, roughness, normal, metalness, displacement].forEach(texture => {
				if (texture) {
					texture.wrapS = this.THREE.RepeatWrapping;
					texture.wrapT = this.THREE.RepeatWrapping;
					texture.repeat.set(1, 1);
					texture.generateMipmaps = true;
					texture.minFilter = this.THREE.LinearMipmapLinearFilter;
					texture.magFilter = this.THREE.LinearFilter;
					texture.anisotropy = 4;
				}
			});

			// Guardar en cache
			this.textureCache.set(texturePath, {
				baseColor, roughness, normal, metalness, displacement
			});

			// Crear material con la misma configuración que WindowModel
			const materialConfig = {
				metalness: 0.8,
				roughness: 0.4,
				envMapIntensity: 0.3,
				clearcoat: 0.1,
				clearcoatRoughness: 0.2,
				opacity: 1.0,
				transparent: false
			};

			if (baseColor) materialConfig.map = baseColor;
			if (metalness) {
				materialConfig.normalMap = metalness;
				materialConfig.normalScale = new this.THREE.Vector2(0.3, 0.3);
			}
			if (roughness) {
				materialConfig.roughnessMap = roughness;
				materialConfig.aoMap = roughness;
				materialConfig.aoMapIntensity = 0.2;
			}
			if (displacement) {
				materialConfig.displacementMap = displacement;
				materialConfig.displacementScale = 0.005;
				materialConfig.displacementBias = -0.002;
			}
			if (useWhiteTexture) {
				materialConfig.color = new this.THREE.Color('#F8F8FF');
				materialConfig.metalness = 0.4;
			}

			return new this.THREE.MeshPhysicalMaterial(materialConfig);
		} catch (error) {
			console.warn('No se pudieron cargar texturas de aluminio, usando color sólido:', error);
			return new this.THREE.MeshPhysicalMaterial({
				color: fallbackColor,
				metalness: 0.85,
				roughness: 0.2,
				clearcoat: 0.3,
				clearcoatRoughness: 0.1
			});
		}
	}

	// Actualizar colores/texturas dinámicamente
	updateColors(parameters) {
		if (!this.model) return;

		const texturePath = parameters.texturePath || '/textures/aluminum/natural/';
		const frameColor = parameters.frameColor || 0xC0C0C0;

		// Crear nuevo material
		const newMaterial = this.createAluminumMaterial(texturePath, frameColor);

		// Actualizar solo los elementos del marco (no la malla)
		this.model.traverse((child) => {
			if (child.isMesh && child.material && !child.material.wireframe) {
				child.material.dispose();
				child.material = newMaterial;
			}
		});
	}

	// Limpiar cache de texturas
	clearCache() {
		this.textureCache.forEach(textureSet => {
			Object.values(textureSet).forEach(texture => {
				if (texture && texture.dispose) texture.dispose();
			});
		});
		this.textureCache.clear();
	}
}

if (typeof window !== 'undefined') {
	window.MeshModel = MeshModel;
}
