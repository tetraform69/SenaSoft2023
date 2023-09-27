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
                <div class="position">
                    <div>
                        {{ x }}
                    </div>
                    <div>
                        {{ y }}
                    </div>
                </div>
            </form>
        </div>

        <div class="cintent-plain w-4/5">
            <canvas
                id="lienzo1"
                class="plano w-full"
                @mousemove="showCoordinates"
                @click="openDialog"
            ></canvas>
        </div>
    </div>

    <dialog id="dialog" class="rounded-md p-3">
        <div class="flex flex-col gap-3">
            <label for="">Nombre de la ubicacion: </label>
            <input v-model="nombre" class="rounded-md" />
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

    <dialog id="nodoInicial">
        <label for="nodo_inicial">Seleccione nodo Inicial:</label>
        <select name="nodo_inicial" id="nodo_inicial" v-model="nodoI">
            <option :value="item" v-for="(item, index) in nodos" :key="index">
                {{ index + 1 }} - {{ item.nombre }}
            </option>
        </select>
        <button @click="guardarRutas">Agregar Ubicacion</button>
    </dialog>

    <div class="flex justify-center mt-4">
        <button
            @click="dialogRutas"
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
            nombre: "",
            canvas: null,
            x: 0,
            y: 0,
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
            this.x = e.offsetX - 512;
            this.y = -(e.offsetY - 250);
            this.dialog.showModal();
        },
        openDialog(e) {
            this.x = e.offsetX - 512;
            this.y = -(e.offsetY - 250);
            this.dialog.showModal();
        },
        showCoordinates(e) {
            this.x = e.offsetX - 512;
            this.y = -(e.offsetY - 250);
        },
        draw() {
            this.canvas.beginPath();
            this.canvas.arc(this.x, this.y, 5, 0, 2 * Math.PI, false);
            this.canvas.stroke();
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
            let xnode = nodo.posicionx + 512;
            let ynode = 250 - nodo.posiciony;
            this.canvas.beginPath();
            this.canvas.arc(xnode, ynode, 5, 0, 2 * Math.PI, false);
            this.canvas.stroke();

            this.canvas.font = "20px serif";
            this.canvas.fillText(nodo.nombre, xnode - 10, ynode - 10);
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
                // console.log(this.conexiones);
                this.conexiones.push(data2);
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
            console.log(conexion);
            this.canvas.beginPath();
            this.canvas.moveTo(
                conexion.nodo_a.posicionx + 512,
                250 - conexion.nodo_a.posiciony
            ); // lo ubicó para iniciar el dibujo
            this.canvas.lineTo(
                conexion.nodo_b.posicionx + 512,
                250 - conexion.nodo_b.posiciony
            ); // trazo la linea hasta este punto
            this.canvas.stroke();
        },
        guardarRutas() {
            dataR = {
                nodo_inicial: this.nodoI,
            };
        },
        getCanvasHW() {
            let c = document.getElementById("lienzo1");
            this.canvasHeight = c.height/2;
            this.canvasWidht = c.width/2;
            console.log(this.canvasHeight);
            console.log(this.canvasWidht);
        },
    },
    //Aqui se monta el plano cartesiano en un canvas.
    mounted() {
        this.getCanvasHW()
        let c = document.getElementById("lienzo1");
        this.dialog = document.getElementById("dialog");
        this.canvas = c.getContext("2d");
        this.canvas.strokeStyle = "#202020";
        this.canvas.lineWidth = 0.5

        //EJEX
        this.canvas.beginPath(); // Pongo el lápiz
        this.canvas.moveTo(this.canvasWidht, 0); // lo ubicó para iniciar el dibujo
        this.canvas.lineTo(this.canvasWidht, this.canvasWidht); // trazo la linea hasta este punto
        this.canvas.stroke(); // levanto el lápiz
        this.canvas.closePath(); // me alisto para realizar otra parte del dibujo
        //EJE Y
        this.canvas.beginPath(); // Pongo el lápiz
        this.canvas.moveTo(0, this.canvasHeight); // lo ubicó para iniciar el dibujo
        this.canvas.lineTo(this.canvasWidht*2, this.canvasHeight); // trazo la linea hasta este punto
        this.canvas.stroke(); // levanto el lápiz
        this.canvas.closePath(); // me alisto para realizar otra parte del dibujo
    },
};
</script>

<style scoped>
.plano {
    background-image: url("../../../public/img/fondo (2).jpg");
    background-position: center;
    border: 1px solid #202020;
    border-radius: 5px;
}

.cintent-plain {
    display: flex;
    justify-content: center;
    align-items: center;
}

.position {
    display: flex;
    align-items: center;
    justify-content: space-evenly;
    gap: 10px;
    width: 100%;
}
</style>
