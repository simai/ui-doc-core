const fs = require('fs');
const path = require('path');

const filesToCopy = [
    'webpack.mix.js',
    'bootstrap.php',
    'config.php',
    'composer.json',
    'tailwind.config.js',
    'eslint.config.js',
    'package.json'
];

filesToCopy.forEach(file => {
    const src = path.resolve(__dirname, file);
    const dest = path.resolve(process.cwd(), file);

    if (fs.existsSync(src)) {
        fs.copyFileSync(src, dest);
        console.log(`✔ copied ${file}`);
    } else {
        console.warn(`⚠ ${file} not found in _core`);
    }
});
