import { convert } from 'pdf-img-convert';
import fs from 'fs';
import path from 'path';

const pdfPath = process.argv[2];
const outputDir = process.argv[3];

if (!pdfPath || !outputDir) {
    console.error(JSON.stringify({
        success: false,
        error: "Missing arguments. Usage: node pdf-to-jpg.js <pdfPath> <outputDir>"
    }));
    process.exit(1);
}

(async () => {
    try {
        if (!fs.existsSync(outputDir)) {
            fs.mkdirSync(outputDir, { recursive: true });
        }

        // pdf-img-convert accepts a file path, URL or buffer
        const outputImages = await convert(pdfPath, {
            width: 1200, // standard width for good resolution
        });

        const imageFiles = [];
        for (let i = 0; i < outputImages.length; i++) {
            const fileName = `page-${i + 1}.jpg`;
            const filePath = path.join(outputDir, fileName);
            fs.writeFileSync(filePath, outputImages[i]);
            imageFiles.push(fileName);
        }

        console.log(JSON.stringify({
            success: true,
            images: imageFiles
        }));
    } catch (error) {
        console.error(JSON.stringify({
            success: false,
            error: error.message
        }));
        process.exit(1);
    }
})();
