/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./assets/**/*.js",
    "./templates/**/*.html.twig",
  ],
  theme: {
    extend: {
      fontFamily: {
        display: ['"Archivo Black"', 'sans-serif'],
        body: ['"Space Grotesk"', 'sans-serif'],
        mono: ['"Space Mono"', 'monospace'],
      },
      colors: {
        ink: 'rgb(var(--color-ink) / <alpha-value>)',
        muted: 'rgb(var(--color-muted) / <alpha-value>)',
        mist: 'rgb(var(--color-mist) / <alpha-value>)',
        paper: 'rgb(var(--color-paper) / <alpha-value>)',
        accent: 'rgb(var(--color-accent) / <alpha-value>)',
        brass: 'rgb(var(--color-brass) / <alpha-value>)',
      },
      boxShadow: {
        soft: '0 12px 30px -18px rgba(15, 23, 42, 0.45)',
      },
    },
  },
  plugins: [],
}
