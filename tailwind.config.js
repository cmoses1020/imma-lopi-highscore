const defaultTheme = require('tailwindcss/defaultTheme')

module.exports = {
    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter var', ...defaultTheme.fontFamily.sans],
            },
            backgroundImage: {
                'pink-pattern': "url('../lopi_assets/cute-pink-background-grid-pattern-pastel-minimal.avif')",
            },
            colors: {
                'lopi-purple': {
                    '50': '#fbf4ff',
                    '100': '#f5e7ff',
                    '200': '#eed3ff',
                    '300': '#e0b0ff',
                    '400': '#cd7eff',
                    '500': '#ba4cff',
                    '600': '#a829f4',
                    '700': '#9219d7',
                    '800': '#7b1aaf',
                    '900': '#561378',
                },
            }
        },
    },
    variants: {
        extend: {
            backgroundColor: ['active'],
        }
    },
    content: [
        './app/**/*.php',
        './resources/**/*.html',
        './resources/**/*.js',
        './resources/**/*.php',
    ],
    plugins: [
        require('@tailwindcss/forms'),
        require('@tailwindcss/typography'),
    ],
}
