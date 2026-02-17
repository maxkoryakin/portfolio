import * as THREE from 'three';
import './styles/app.css';

const THEME_STORAGE_KEY = 'portfolio-theme';
const root = document.documentElement;

const readStoredTheme = () => {
  try {
    return window.localStorage.getItem(THEME_STORAGE_KEY);
  } catch (_error) {
    return null;
  }
};

const writeStoredTheme = (theme) => {
  try {
    window.localStorage.setItem(THEME_STORAGE_KEY, theme);
  } catch (_error) {
    // Ignore storage failures (private mode or blocked storage).
  }
};

const getInitialTheme = () => {
  const storedTheme = readStoredTheme();
  if (storedTheme === 'light' || storedTheme === 'dark') {
    return storedTheme;
  }

  return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
};

const updateThemeControls = (theme) => {
  const toggleButtons = document.querySelectorAll('[data-theme-toggle]');
  for (const button of toggleButtons) {
    if (!(button instanceof HTMLButtonElement)) {
      continue;
    }

    button.setAttribute('aria-pressed', theme === 'dark' ? 'true' : 'false');
  }

  const toggleLabels = document.querySelectorAll('[data-theme-label]');
  for (const label of toggleLabels) {
    const parentToggle = label.closest('[data-theme-toggle]');
    const nextThemeText = theme === 'dark'
      ? parentToggle?.getAttribute('data-theme-light-text')
      : parentToggle?.getAttribute('data-theme-dark-text');

    if (nextThemeText) {
      label.textContent = nextThemeText;
    }
  }
};

const applyTheme = (theme, persist = true) => {
  root.classList.toggle('theme-dark', theme === 'dark');
  root.setAttribute('data-theme', theme);
  updateThemeControls(theme);
  window.dispatchEvent(new CustomEvent('portfolio:themechange', { detail: { theme } }));
  if (persist) {
    writeStoredTheme(theme);
  }
};

const setupSpotlightBackground = () => {
  const spotlightCanvas = document.querySelector('[data-spotlight-canvas]');
  const finePointer = window.matchMedia('(pointer: fine)').matches;
  const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  if (!(spotlightCanvas instanceof HTMLCanvasElement) || !finePointer || reduceMotion) {
    return;
  }

  let renderer;
  try {
    renderer = new THREE.WebGLRenderer({
      canvas: spotlightCanvas,
      alpha: true,
      antialias: false,
      powerPreference: 'low-power',
      premultipliedAlpha: true,
    });
  } catch (_error) {
    return;
  }

  renderer.setPixelRatio(Math.min(window.devicePixelRatio || 1, 1.6));
  renderer.setSize(window.innerWidth, window.innerHeight, false);

  const scene = new THREE.Scene();
  const camera = new THREE.OrthographicCamera(-1, 1, 1, -1, 0, 1);

  const uniforms = {
    uResolution: { value: new THREE.Vector2(window.innerWidth, window.innerHeight) },
    uPointer: { value: new THREE.Vector2(0.5, 0.5) },
    uVelocity: { value: new THREE.Vector2(0, 0) },
    uLean: { value: 0 },
    uTime: { value: 0 },
    uTheme: { value: root.classList.contains('theme-dark') ? 1 : 0 },
  };

  const geometry = new THREE.PlaneGeometry(2, 2);
  const material = new THREE.ShaderMaterial({
    uniforms,
    transparent: true,
    depthWrite: false,
    blending: THREE.NormalBlending,
    vertexShader: `
      varying vec2 vUv;

      void main() {
        vUv = uv;
        gl_Position = vec4(position, 1.0);
      }
    `,
    fragmentShader: `
      precision highp float;
      varying vec2 vUv;

      uniform vec2 uResolution;
      uniform vec2 uPointer;
      uniform vec2 uVelocity;
      uniform float uLean;
      uniform float uTime;
      uniform float uTheme;

      float hash(vec2 p) {
        return fract(sin(dot(p, vec2(127.1, 311.7))) * 43758.5453123);
      }

      float noise(vec2 p) {
        vec2 i = floor(p);
        vec2 f = fract(p);

        float a = hash(i);
        float b = hash(i + vec2(1.0, 0.0));
        float c = hash(i + vec2(0.0, 1.0));
        float d = hash(i + vec2(1.0, 1.0));

        vec2 u = f * f * (3.0 - 2.0 * f);
        return mix(a, b, u.x) +
          (c - a) * u.y * (1.0 - u.x) +
          (d - b) * u.x * u.y;
      }

      float fbm(vec2 p) {
        float value = 0.0;
        float amp = 0.5;
        for (int i = 0; i < 5; i++) {
          value += amp * noise(p);
          p *= 2.03;
          amp *= 0.5;
        }
        return value;
      }

      void main() {
        vec2 uv = vUv;
        vec2 pointer = uPointer;
        float aspect = uResolution.x / max(uResolution.y, 1.0);

        vec2 flowUv = uv * vec2(aspect, 1.0);
        float flowA = fbm(flowUv * 2.2 + vec2(uTime * 0.018, -uTime * 0.013));
        float flowB = fbm(flowUv * 3.8 - vec2(uTime * 0.011, uTime * 0.016));
        float flow = mix(flowA, flowB, 0.45);

        vec2 velocity = uVelocity * vec2(aspect, 1.0);
        float speed = clamp(length(velocity) * 52.0, 0.0, 1.0);

        vec2 pointerDelta = (uv - pointer) * vec2(aspect, 1.0);
        float along = pointerDelta.y;
        float across = pointerDelta.x;

        float tilt = uLean * (0.34 + speed * 0.36);
        across += along * tilt;
        along += (flow - 0.5) * (0.046 + speed * 0.05);

        float topMask = smoothstep(-0.18, 0.58, along);
        float width = mix(0.18, 0.11, topMask);

        float flameShape = exp(
          -pow(across / max(width, 0.03), 2.0)
          -pow((along - 0.03) / 0.52, 2.0)
        );
        float innerShape = exp(
          -pow(across / max(width * 0.52, 0.018), 2.0)
          -pow((along - 0.01) / 0.28, 2.0)
        );
        float auraShape = exp(
          -pow(across / 0.34, 2.0)
          -pow((along + 0.01) / 0.60, 2.0)
        );

        vec3 coolA = vec3(0.19, 0.56, 1.0);
        vec3 coolB = vec3(0.50, 0.39, 1.0);
        vec3 warmEdge = vec3(1.0, 0.60, 0.34);
        vec3 warmCore = vec3(1.0, 0.82, 0.50);
        vec3 baseColor = mix(coolA, coolB, flow);
        vec3 flameColor = mix(warmEdge, warmCore, innerShape);
        flameColor = mix(flameColor, coolA, 0.14 + flow * 0.12);
        vec3 color = mix(baseColor, flameColor, clamp(flameShape * 0.78 + innerShape * 0.55, 0.0, 1.0));

        float vignette = smoothstep(1.08, 0.18, length((uv - 0.5) * vec2(aspect, 1.0)));
        float alpha = (0.038 + flow * 0.036) * vignette * mix(1.28, 0.93, uTheme);
        alpha += flameShape * mix(0.116, 0.080, uTheme);
        alpha += innerShape * mix(0.132, 0.095, uTheme);
        alpha += auraShape * mix(0.033, 0.022, uTheme);
        alpha = clamp(alpha, 0.0, 0.28);

        gl_FragColor = vec4(color, alpha);
      }
    `,
  });

  const quad = new THREE.Mesh(geometry, material);
  scene.add(quad);

  const targetPointer = new THREE.Vector2(0.5, 0.5);
  const smoothedPointer = new THREE.Vector2(0.5, 0.5);
  const targetVelocity = new THREE.Vector2(0, 0);
  const smoothedVelocity = new THREE.Vector2(0, 0);
  const lastPointer = new THREE.Vector2(window.innerWidth * 0.5, window.innerHeight * 0.5);
  let lean = 0;
  let leanVelocity = 0;
  const clock = new THREE.Clock();
  let rafId = 0;

  const updatePointer = (event) => {
    const dx = (event.clientX - lastPointer.x) / Math.max(window.innerWidth, 1);
    const dy = (lastPointer.y - event.clientY) / Math.max(window.innerHeight, 1);

    lastPointer.set(event.clientX, event.clientY);
    targetPointer.set(
      event.clientX / window.innerWidth,
      1 - (event.clientY / window.innerHeight)
    );
    targetVelocity.set(dx, dy).clampLength(0, 0.065);
  };

  const updateSize = () => {
    const width = window.innerWidth;
    const height = window.innerHeight;
    renderer.setPixelRatio(Math.min(window.devicePixelRatio || 1, 1.6));
    renderer.setSize(width, height, false);
    uniforms.uResolution.value.set(width, height);
  };

  const render = () => {
    rafId = requestAnimationFrame(render);
    uniforms.uTime.value = clock.getElapsedTime();
    targetVelocity.multiplyScalar(0.92);
    smoothedPointer.lerp(targetPointer, 0.07);
    smoothedVelocity.lerp(targetVelocity, 0.12);

    const speed = smoothedVelocity.length();
    const leanTarget = THREE.MathUtils.clamp(-smoothedVelocity.x * 38.0, -1.15, 1.15);
    const spring = speed > 0.0016 ? 0.23 : 0.14;
    const damping = speed > 0.0016 ? 0.76 : 0.82;
    leanVelocity += (leanTarget - lean) * spring;
    leanVelocity *= damping;
    lean += leanVelocity;

    uniforms.uPointer.value.copy(smoothedPointer);
    uniforms.uVelocity.value.copy(smoothedVelocity);
    uniforms.uLean.value = lean;
    renderer.render(scene, camera);
  };

  const onThemeChange = (event) => {
    if (!(event instanceof CustomEvent) || typeof event.detail?.theme !== 'string') {
      return;
    }

    uniforms.uTheme.value = event.detail.theme === 'dark' ? 1 : 0;
  };

  window.addEventListener('pointermove', updatePointer, { passive: true });
  window.addEventListener('resize', updateSize);
  window.addEventListener('portfolio:themechange', onThemeChange);

  render();

  window.addEventListener('beforeunload', () => {
    cancelAnimationFrame(rafId);
    window.removeEventListener('pointermove', updatePointer);
    window.removeEventListener('resize', updateSize);
    window.removeEventListener('portfolio:themechange', onThemeChange);
    geometry.dispose();
    material.dispose();
    renderer.dispose();
  }, { once: true });
};

applyTheme(getInitialTheme(), false);

for (const button of document.querySelectorAll('[data-theme-toggle]')) {
  if (!(button instanceof HTMLButtonElement)) {
    continue;
  }

  button.addEventListener('click', () => {
    const currentTheme = root.classList.contains('theme-dark') ? 'dark' : 'light';
    const nextTheme = currentTheme === 'dark' ? 'light' : 'dark';
    applyTheme(nextTheme, true);
  });
}

setupSpotlightBackground();

const revealElements = document.querySelectorAll('[data-reveal]');

if (revealElements.length > 0 && 'IntersectionObserver' in window) {
  const revealObserver = new IntersectionObserver((entries, observer) => {
    for (const entry of entries) {
      if (entry.isIntersecting) {
        entry.target.classList.add('is-visible');
        observer.unobserve(entry.target);
      }
    }
  }, {
    threshold: 0.14,
  });

  for (const element of revealElements) {
    revealObserver.observe(element);
  }
}

const counterElements = document.querySelectorAll('[data-counter]');

for (const counterElement of counterElements) {
  const end = Number(counterElement.getAttribute('data-counter'));

  if (Number.isNaN(end)) {
    continue;
  }

  const duration = 850;
  const startTime = performance.now();

  const render = (now) => {
    const progress = Math.min((now - startTime) / duration, 1);
    const value = Math.floor(progress * end);
    counterElement.textContent = String(value);

    if (progress < 1) {
      requestAnimationFrame(render);
    }
  };

  requestAnimationFrame(render);
}

const navToggle = document.querySelector('[data-nav-toggle]');
const mobileNav = document.querySelector('[data-mobile-nav]');

if (navToggle instanceof HTMLButtonElement && mobileNav instanceof HTMLElement) {
  navToggle.addEventListener('click', () => {
    mobileNav.classList.toggle('hidden');
    const expanded = navToggle.getAttribute('aria-expanded') === 'true';
    navToggle.setAttribute('aria-expanded', expanded ? 'false' : 'true');
  });
}
