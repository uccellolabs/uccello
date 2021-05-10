module.exports = {
  purge: [
    './resources/**/*.blade.php',
    './resources/**/*.js',
    './resources/**/*.vue',
  ],
  darkMode: false, // or 'media' or 'class'
  theme: {
    fontSize: {
      'xxs': '.6rem', 
      'xs': '.75rem',
      'sm': '.875rem',
      'tiny': '.95rem',
      'base': '1rem',
      'lg': '1.125rem',
      'xl': '1.25rem',
      '2xl': '1.5rem',
      '3xl': '1.875rem',
      '4xl': '2.25rem',
      '5xl': '3rem',
      '6xl': '4rem',
      '7xl': '5rem',
    },
    
    extend: {
      height: {
        table_lg: '74vh',
       },
      colors: {
        primary: {
          500: '#007ADD', // Blue Uccello
          900: '#0B2540', // Blue text
        },
        blue: {
          backgroundIcon: '#ebf5fc', // couleur de fond des icons
        },
        orange:{
          100: '#F9EBE8',
          500: '#FC6534',
        },
        green: {
          500: '#28CA90',
        },
        purple: {
          500: '#7E54EF',
        },
        red: {
          500: '#CA2828',
        },
      },
    }

  },
  variants: {
    scrollbar: ['rounded'],
    extend: {
      backgroundColor: ['checked'],
      borderColor: ['checked'],
    }
  },
  plugins: [
    require('tailwind-scrollbar')
],
}
