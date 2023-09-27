<template>
    <div class="flex flex-row gap-5">
        <div class="cintent-plain w-full">
            <canvas id="lienzo1" class="plano" height="700" width="700" @click="openDialog"></canvas>
        </div>
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
            rutas: [],
            ruta_id: 0,
            nombre: "",
            canvas: null,
            ctx: null,
            x: 0,
            y: 0,
            grid_size: 25,  
            canvasHeight: 0,
            canvasWidht: 0,
        };
    },
    methods: {
        obtenerGrafo() {
            axios.get(`/api/ruta?id=${this.ruta_id}`).then((r) => {
                console.log(r.data);
                this.ctx.reset();
                this.grid()
                r.data.ubicaciones.forEach((node) => {
                    console.log(node);
                    this.drawNode(node);
                });
                r.data.conexiones.forEach((conexion) => {
                    console.log(conexion);
                    this.drawLine(conexion);
                });
            });
        },
        drawNode(nodo) {
            var x_cero = 350;
            var y_cero = 350;
            let xnode = x_cero + (nodo.posicionx * this.grid_size);
            let ynode = y_cero - (nodo.posiciony * this.grid_size);
            this.ctx.beginPath();
            this.ctx.arc(xnode, ynode, 2, 0, 2 * Math.PI, false);
            this.ctx.stroke();

            this.ctx.font = "20px serif";
            this.ctx.fillText(nodo.nombre, xnode - 10, ynode - 10);
        },
        drawLine(conexion) {
            var x_cero = 350;
            var y_cero = 350;
            console.log(conexion);
            this.ctx.beginPath();
            this.ctx.moveTo(
                x_cero + (conexion.ubicacion1.posicionx * this.grid_size),
                y_cero - (conexion.ubicacion1.posiciony * this.grid_size)
            ); // lo ubicó para iniciar el dibujo
            this.ctx.lineTo(
                x_cero + (conexion.ubicacion2.posicionx * this.grid_size),
                y_cero - (conexion.ubicacion2.posiciony * this.grid_size)
            ); // trazo la linea hasta este punto
            this.ctx.stroke();
        },
        obtenerRutas() {
            axios.get("/api/ruta/").then((rest) => {
                this.rutas = rest.data;
            });
        },
        getCanvasHW() {
            let c = document.getElementById("lienzo1");
            this.canvasHeight = c.height;
            this.canvasWidht = c.width;
        },
        grid() {
            this.ctx.beginPath();
            var x_axis_size = this.canvasWidht - (2 * this.grid_size);
            var y_axis_size = this.canvasHeight - (2 * this.grid_size);
            var x_cero = 350;
            var y_cero = 350;
            // Cuadriculas X
            for (var x = 0; x <= this.canvasWidht; x = x + this.grid_size) {
                this.ctx.moveTo(x, 0);
                this.ctx.lineTo(x, this.canvasHeight);
            }

            // Cuadrículas Y
            for (var y = 0; y <= this.canvasWidht; y = y + this.grid_size) {
                this.ctx.moveTo(0, y);
                this.ctx.lineTo(this.canvasWidht, y);
            }

            // Mueves el pencil y luego persistes con stroke
            this.ctx.strokeStyle = "#cdcdcd";
            this.ctx.lineWidth = 1;
            this.ctx.stroke();

            // Eje x del 0
            this.ctx.beginPath();
            this.ctx.moveTo(x_cero - (x_axis_size / 2), y_cero);
            this.ctx.lineTo(x_cero + (x_axis_size / 2), y_cero);
            // Eje y del 0
            this.ctx.moveTo(x_cero, y_cero - (y_axis_size / 2));
            this.ctx.lineTo(x_cero, y_cero + (y_axis_size / 2));
            this.ctx.strokeStyle = "#000";
            this.ctx.lineWidth = 2;
            this.ctx.stroke();

            // Ponemos números
            this.ctx.font = "bold 10px sans-serif";

            // Los de Y
            x = 0;
            while (x * this.grid_size <= (y_axis_size / 2)) {
                this.ctx.textAlign = "end";
                this.ctx.fillText(x, x_cero - (this.grid_size / 4), y_cero - (this.grid_size * x));
                x++;
            }

            x = 1;
            while (x * this.grid_size <= (y_axis_size / 2)) {
                this.ctx.textAlign = "end";
                this.ctx.fillText(-x, x_cero - (this.grid_size / 4), y_cero + (this.grid_size * x));
                x++;
            }

            // Los de X

            x = 1;
            while (x * this.grid_size <= (x_axis_size / 2)) {
                this.ctx.textAlign = "end";
                this.ctx.fillText(x, x_cero + (this.grid_size * x), y_cero + (this.grid_size / 2));
                x++;
            }

            x = 1;
            while (x * this.grid_size <= (x_axis_size / 2)) {
                this.ctx.textAlign = "end";
                this.ctx.fillText(-x, x_cero - (this.grid_size * x), y_cero + (this.grid_size / 2));
                x++;
            }
        },
    },
    mounted() {
        this.getCanvasHW();
        let c = document.getElementById("lienzo1");
        this.ctx = c.getContext("2d");
        this.canvas = c;
        this.grid()
        this.obtenerRutas();
    },
};
</script>

<style scoped>
.plano {
    border: 1px solid #202020;
    border-radius: 5px;
}

.cintent-plain {
    display: flex;
    justify-content: center;
    align-items: center;
}
</style>
