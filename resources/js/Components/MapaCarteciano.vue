<template>
    <div class="flex flex-row gap-5">
        <div class="bg-slate-300 mt-4 rounded-md p-3 w-1/5">
            <form>
                <select
                    name="nodo_A"
                    id="nodo_A"
                    v-model="nodoA"
                    class="rounded-md mb-5 w-full"
                >
                    <option value="0" selected disabled>
                        Seleccione nodo A
                    </option>
                    <option
                        :value="item"
                        v-for="(item, index) in nodos"
                        :key="index"
                    >
                        {{ index + 1 }} - {{ item.nombre }}
                    </option>
                </select>
                <select
                    name="nodo_B"
                    id="nodo_B"
                    v-model="nodoB"
                    class="rounded-md mb-5 w-full"
                >
                    <option value="0" selected disabled>
                        Seleccione nodo B
                    </option>
                    <option
                        :value="item2"
                        v-for="(item2, index) in nodos"
                        :key="index"
                    >
                        {{ index + 1 }} - {{ item2.nombre }}
                    </option>
                </select>
                <div class="flex flex-row flex justify-center">
                    <input
                        type="number"
                        v-model="peso"
                        min="1"
                        id="peso"
                        class="rounded-md w-full mb-5"
                        placeholder="Ingrese Peso (distancia):"
                    />
                </div>
                <div class="flex flex-row flex justify-center">
                    <button
                        type="button"
                        @click="agregarConexion"
                        class="inline-flex items-center px-4 py-3 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
                    >
                        Agregar Conexion
                    </button>
                </div>
            </form>
        </div>

        <div class="cintent-plain w-4/5">
            <canvas
                id="lienzo1"
                class="plano"
                height="700"
                width="700"
                @click="openDialog"
            ></canvas>
        </div>
    </div>

    <dialog id="dialog" class="rounded-md p-3">
        <div class="flex flex-col gap-3">
            <label for="">Nombre de la ubicacion: </label>
            <input v-model="nombre" class="rounded-md" />
            <label for="">Posicion X: </label>
            <input v-model="x" class="rounded-md" />
            <label for="">Posicion Y: </label>
            <input v-model="y" class="rounded-md" />
            <div class="flex gap-3">
                <button
                    @click="agregarNodos"
                    class="inline-flex items-center px-4 py-3 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
                >
                    agregar
                </button>
                <button
                    @click="dialog.close()"
                    class="inline-flex items-center px-4 py-3 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
                >
                    cancelar
                </button>
            </div>
        </div>
    </dialog>

    <dialog id="nodoInicial" class="rounded-md p-3">
        <div class="flex flex-col gap-3">
            <label for="nodo_inicial">Seleccione nodo Inicial:</label>
            <select
                name="nodo_inicial"
                id="nodo_inicial"
                v-model="nodoI"
                class="rounded-md"
            >
                <option
                    :value="item"
                    v-for="(item, index) in nodos"
                    :key="index"
                >
                    {{ index + 1 }} - {{ item.nombre }}
                </option>
            </select>
            <div class="flex gap-3">
                <button
                    @click="guardarRuta"
                    class="inline-flex items-center px-4 py-3 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
                >
                    Agregar Ubicacion
                </button>
                <button
                    @click="dialogNodoInicial.close()"
                    class="inline-flex items-center px-4 py-3 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
                >
                    cancelar
                </button>
            </div>
        </div>
    </dialog>

    <div class="flex justify-center mt-4">
        <button
            @click="openNodoInicial"
            class="inline-flex items-center px-4 py-3 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
        >
            Guardar rutas
        </button>
    </div>
</template>

<script>
import axios from "axios";
export default {
    data() {
        return {
            dialog: null,
            dialogNodoInicial: null,
            nombre: "",
            canvas: null,
            ctx: null,
            x: 0,
            y: 0,
            grid_size: 25,
            nodos: [],
            nodoA: 0,
            nodoB: 0,
            nodoI: 0,
            peso: "",
            conexiones: [],
            canvasHeight: 0,
            canvasWidht: 0,
        };
    },
    methods: {
        openDialog(e) {
            this.dialog.showModal();
        },
        openNodoInicial(e) {
            this.dialogNodoInicial.showModal();
        },
        obtenerNodos() {
            axios
                .get("/api/nodos/")
                .then((response) => {
                    console.log(response);
                    this.nodos = response.data;
                    response.data.forEach((nodo) => {
                        this.drawNode(nodo);
                    });
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        drawNode(nodo) {
            var x_cero = 350;
            var y_cero = 350;
            let xnode = x_cero + nodo.posicionx * this.grid_size;
            let ynode = y_cero - nodo.posiciony * this.grid_size;
            this.ctx.beginPath();
            this.ctx.arc(xnode, ynode, 2, 0, 2 * Math.PI, false);
            this.ctx.stroke();

            this.ctx.font = "20px serif";
            this.ctx.fillText(nodo.nombre, xnode - 10, ynode - 10);
        },
        //Agrega nodos a un arrelgo.
        agregarNodos() {
            let data = {
                nombre: this.nombre,
                posicionx: this.x,
                posiciony: this.y,
            };

            this.drawNode(data);
            this.nodos.push(data);
            this.nombre = "";
            this.dialog.close();
            // axios.post('/api/nodos/', data)
            //     .then(r => {
            //         console.log(r.data);
            //     })
            //     .catch(error => {
            //         console.error(error);
            //     })
        },
        agregarConexion() {
            if (this.nodoA === this.nodoB) {
                alert("No puede crear una conexion utilizando el mismo nodo");
            } else {
                let data2 = {
                    peso: this.peso,
                    nodo_a: this.nodoA,
                    nodo_b: this.nodoB,
                };
                this.conexiones.push(data2);
                console.log(this.conexiones);
                this.drawLine(data2);
                // axios
                //     .post("/api/conexion/", data)
                //     .then((rest) => {
                //         console.log("Todo Chil");
                //         console.log(rest.data);
                //     })
                //     .catch((error) => {
                //         console.error(error);
                //     });
            }
        },
        drawLine(conexion) {
            var x_cero = 350;
            var y_cero = 350;
            console.log(conexion);
            this.ctx.beginPath();
            this.ctx.moveTo(
                x_cero + conexion.nodo_a.posicionx * this.grid_size,
                y_cero - conexion.nodo_a.posiciony * this.grid_size
            ); // lo ubicó para iniciar el dibujo
            this.ctx.lineTo(
                x_cero + conexion.nodo_b.posicionx * this.grid_size,
                y_cero - conexion.nodo_b.posiciony * this.grid_size
            ); // trazo la linea hasta este punto
            this.ctx.stroke();
        },
        guardarRuta() {
            let data = {
                nodo_inicial: this.nodoI,
                ubicaciones: this.nodos,
                conexiones: this.conexiones,
            };

            axios
                .post("/api/ruta", data)
                .then((res) => {
                    this.dialogNodoInicial.close()
                    window.location = "/calcularRutas";
                })
                .catch((err) => {
                    console.error(err);
                });
        },
        getCanvasHW() {
            let c = document.getElementById("lienzo1");
            this.canvasHeight = c.height;
            this.canvasWidht = c.width;
        },
        grid() {
            this.ctx.beginPath();
            var x_axis_size = this.canvasWidht - 2 * this.grid_size;
            var y_axis_size = this.canvasHeight - 2 * this.grid_size;
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
            this.ctx.moveTo(x_cero - x_axis_size / 2, y_cero);
            this.ctx.lineTo(x_cero + x_axis_size / 2, y_cero);
            // Eje y del 0
            this.ctx.moveTo(x_cero, y_cero - y_axis_size / 2);
            this.ctx.lineTo(x_cero, y_cero + y_axis_size / 2);
            this.ctx.strokeStyle = "#000";
            this.ctx.lineWidth = 2;
            this.ctx.stroke();

            // Ponemos números
            this.ctx.font = "bold 10px sans-serif";

            // Los de Y
            x = 0;
            while (x * this.grid_size <= y_axis_size / 2) {
                this.ctx.textAlign = "end";
                this.ctx.fillText(
                    x,
                    x_cero - this.grid_size / 4,
                    y_cero - this.grid_size * x
                );
                x++;
            }

            x = 1;
            while (x * this.grid_size <= y_axis_size / 2) {
                this.ctx.textAlign = "end";
                this.ctx.fillText(
                    -x,
                    x_cero - this.grid_size / 4,
                    y_cero + this.grid_size * x
                );
                x++;
            }

            // Los de X

            x = 1;
            while (x * this.grid_size <= x_axis_size / 2) {
                this.ctx.textAlign = "end";
                this.ctx.fillText(
                    x,
                    x_cero + this.grid_size * x,
                    y_cero + this.grid_size / 2
                );
                x++;
            }

            x = 1;
            while (x * this.grid_size <= x_axis_size / 2) {
                this.ctx.textAlign = "end";
                this.ctx.fillText(
                    -x,
                    x_cero - this.grid_size * x,
                    y_cero + this.grid_size / 2
                );
                x++;
            }
        },
    },
    //Aqui se monta el plano cartesiano en un canvas.
    mounted() {
        this.getCanvasHW();
        let c = document.getElementById("lienzo1");
        this.ctx = c.getContext("2d");
        this.canvas = c;
        this.dialog = document.getElementById("dialog");
        this.dialogNodoInicial = document.getElementById("nodoInicial");
        this.grid();
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
