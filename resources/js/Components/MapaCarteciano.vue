<template>
    <span>{{ x }}, {{ y }}</span>
    <div class="cintent-plain">
        <canvas id="lienzo1" width="1024" height="500" class="plano" @mousemove="showCoordinates"></canvas>  
    </div>
</template>

<script>
export default {
    data() {
        return {
            canvas: null,
            x: 0,
            y: 0,
        }
    },
    methods: {
        print() {
            console.log(this.canvas)
        },
        showCoordinates(e) {
            this.x = e.offsetX - 512;
            this.y = -(e.offsetY - 250);
        }
    },
    mounted() {
        var c = document.getElementById("lienzo1")
        this.canvas = c.getContext("2d")
        this.canvas.strokeStyle = 'rgb(0,0,0)'

        //EJEX
        this.canvas.beginPath(); // Pongo el lápiz
        this.canvas.moveTo(512, 0); // lo ubicó para iniciar el dibujo
        this.canvas.lineTo(512, 512); // trazo la linea hasta este punto
        this.canvas.stroke(); // levanto el lápiz
        this.canvas.closePath(); // me alisto para realizar otra parte del dibujo
        //EJE Y
        this.canvas.beginPath(); // Pongo el lápiz
        this.canvas.moveTo(0, 250);// lo ubicó para iniciar el dibujo
        this.canvas.lineTo(1024, 250);// trazo la linea hasta este punto
        this.canvas.stroke();// levanto el lápiz
        this.canvas.closePath();// me alisto para realizar otra parte del dibujo
    },
}
</script>

<style scoped>
.plano {
    background-image: url('../../../public/img/fondo (2).jpg');
    background-position: center;
}
.cintent-plain{
    display: flex;
    justify-content: center;
    align-items: center;
}
</style>