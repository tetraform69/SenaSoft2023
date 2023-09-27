<template>
    <div class="my-3 flex justify-center ">
            <button @click="obtenerNodos" class="bg-slate-200 shadow-lg shadow-slate-300 rounded-full py-2 px-3">get nodos</button>
    </div>
    <span>{{ x }}, {{ y }}</span>
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
    <dialog id="dialog">
        <label for="">Nombre de la ubicacion: </label>
        <input v-model="nombre" />
        <button @click="agregarNodos">Agregar Ubicacion</button>
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
    <br />
    <hr />
    <br />
    <div>
        <form>
            <div class="">
                <label for="nodo_A">selecione nodo 1:</label>
                <select name="nodo_A" id="nodo_A" v-model="nodoA">
                    <option
                        :value="item"
                        v-for="(item, index) in nodos"
                        :key="index"
                    >
                        {{ index + 1 }} - {{ item.nombre }}
                    </option>
                </select>
            </div>
            <div class="">
                <label for="nodo_B">selecione nodo 2:</label>
                <select name="nodo_B" id="nodo_B" v-model="nodoB">
                    <option
                        :value="item2"
                        v-for="(item2, index) in nodos"
                        :key="index"
                    >
                        {{ index + 1 }} - {{ item2.nombre }}
                    </option>
                </select>
            </div>
            <div>
                <label for="peso">Ingrese Peso (distancia) : </label>
                <input type="number" v-model="peso" min="1" id="peso" />
            </div>
            <div>
                <button type="button" @click="agregarConexion">
                    Agregar Conexion
                </button>
            </div>
        </form>
    </div>
    <br><br>
    <div class="flex jusfity-center">
        <button @click="dialogRutas">Guardar rutas</button>
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
                nodo_inicial: this.nodoI
            }
        },
    },
    //Aqui se monta el plano cartesiano en un canvas.
    mounted() {
        var c = document.getElementById("lienzo1");
        this.dialog = document.getElementById("dialog");
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
</style>
