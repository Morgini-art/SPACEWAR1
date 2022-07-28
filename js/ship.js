class Ship {
    constructor(image, width, height, cannon, bulletWidth) {
        this.image = new Image();
        this.width = width;
        this.height = height;
        this.image.src = image;
        this.cannon = cannon;
        this.bulletWidth = bulletWidth;
    }
}


export class Ship{