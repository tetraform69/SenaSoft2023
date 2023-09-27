<template>
    <div class="cintent-plain">
        <canvas
            id="lienzo1"
            width="1024"
            height="500"
            class="plano"
            @mousemove="showCoordinates"
            @click="openDialog"
        ></canvas>
    </div>
    <div class="flex flex-row jusfity-center my-4">
        <div>
            <select
                name="ruta"
                id="ruta"
                v-model="ruta_id"
                @change="obtenerGrafo"
                class="mx-4 rounded-md"
            >
                <option value="0" selected disabled>
                    Seleccione nodo incicial
                </option>
                <option
                    :value="item.id"
                    v-for="(item, index) in rutas"
                    :key="index"
                >
                    {{ index + 1 }} - {{ item.nodo_inicial.nombre }}
                </option>
            </select>
        </div>
        <div class="mx-3">
            <button
                class="inline-flex items-center px-4 py-3 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
                @click="obtenerGrafo"
            >
                Ver la ruta mas Eficiente
            </button>
        </div>
    </div>
</template>

<script>
import axios from "axios";

export default {
    data() {
        return {
            canvas: null,
            rutas: [],
            ruta_id: 0,
        };
    },
    methods: {
        obtenerGrafo() {
            axios.get(`/api/ruta?id=${this.ruta_id}`).then((r) => {
                console.log(r.data);
                this.canvas.reset();
                this.initCanvas()
                r.data.ubicaciones.forEach((node) => {
                    this.drawNode(node);
                });
                r.data.conexiones.forEach((conexion) => {
                    this.drawLine(conexion);
                });
            });
        },
        drawNode(nodo) {
            let xnode = nodo.posicionx + 512;
            let ynode = 250 - nodo.posiciony;

            this.canvas.strokeStyle = "rgb(0 0 0 / 50%)";
            this.canvas.beginPath();
            this.canvas.arc(xnode, ynode, 5, 0, 2 * Math.PI, false);
            this.canvas.stroke();

            this.canvas.font = "20px serif";
            this.canvas.fillText(nodo.nombre, xnode - 10, ynode - 10);
        },
        drawLine(conexion) {
            console.log(conexion);
            this.canvas.beginPath();
            this.canvas.moveTo(
                conexion.ubicacion1.posicionx + 512,
                250 - conexion.ubicacion1.posiciony
            ); // lo ubicó para iniciar el dibujo
            this.canvas.lineTo(
                conexion.ubicacion2.posicionx + 512,
                250 - conexion.ubicacion2.posiciony
            ); // trazo la linea hasta este punto
            this.canvas.stroke();
        },
        obtenerRutas() {
            axios.get("/api/ruta/").then((rest) => {
                console.log(rest);
                this.rutas = rest.data;
            });
        },
        initCanvas() {
            var c = document.getElementById("lienzo1");
            this.canvas = c.getContext("2d");
            this.canvas.strokeStyle = "rgb(0,0,0)";

            //EJEX
            this.canvas.beginPath(); // Pongo el lápiz
            this.canvas.moveTo(512, 0); // lo ubicó para iniciar el dibujo
            this.canvas.lineTo(512, 512); // trazo la linea hasta este punto
            this.canvas.stroke(); // levanto el lápiz
            this.canvas.closePath(); // me alisto para realizar otra parte del dibujo
            //EJE Y
            this.canvas.beginPath(); // Pongo el lápiz
            this.canvas.moveTo(0, 250); // lo ubicó para iniciar el dibujo
            this.canvas.lineTo(1024, 250); // trazo la linea hasta este punto
            this.canvas.stroke(); // levanto el lápiz
            this.canvas.closePath(); // me alisto para realizar otra parte del dibujo
        },
    },
    mounted() {
        this.initCanvas()
        this.obtenerRutas();
    },
};
</script>

<style scoped>
.cintent-plain {
    display: flex;
    justify-content: center;
    align-items: center;
}
.plano {
    background-image: url("../../../public/img/fondo (2).jpg");
    background-position: center;
}
</style>
