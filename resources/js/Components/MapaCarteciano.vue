<template>
    <button @click="obtenerNodos">get nodos</button>
    <span>{{ x }}, {{ y }}</span>
    <div class="cintent-plain">
        <canvas id="lienzo1" width="1024" height="500" class="plano" @mousemove="showCoordinates" @click="openDialog"></canvas>
    </div>
    <dialog id="dialog">
        <label for="">Nombre de la ubicacion: </label>
        <input v-model="nombre">
        <button @click="agregarNodos">Agregar Ubicacion</button>
    </dialog>
    <br>
    <hr>
    <br>
    <div>
        <form @submit.prevent="agregarConexion">
            <div>
                <label for="">selecione nodo 1: </label>
                <select name="" id="" v-model="nodo_a">
                    <template v-for="(item, index) in nodos" :key="index">
                        <option value="item.id">{{ item.id }} - {{ item.nombre}}</option>
                    </template>
                </select>
            </div>
            <div>
                <label for="">selecione nodo 2:</label>
                <select name="" id="" v-model="nodo_b">
                    <template v-for="(item, index) in nodos" :key="index">
                        <option value="item.id">{{ item.id }} - {{ item.nombre}}</option>
                    </template>
                </select>
            </div>
            <div>
                <label for="">Ingrese Peso (distancia) : </label>
                <input type="number" v-model="peso" min="1">
            </div>
            <div>
                <button>Agregar Conexion</button>
            </div>
        </form>
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
            xini: 0,
            xfin: 0,
            yini: 0,
            yfin: 0,
            nodos: null,
            px: 0,
            px2: 0,
            py: 0,
            py2: 0,
            nodo_a: null,
            nodo_b: null,
            peso:0,
        }
    },
    methods: {
        openDialog(e){
            console.log(e.offsetX);
            console.log(e.offsetY);
            console.log(this.x);
            console.log(this.y);
            this.x = e.offsetX - 512;
            this.y = -(e.offsetY - 250);
            this.dialog.showModal()
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
            axios.get('/api/nodos/')
                .then(response => {
                    console.log(response);
                    this.nodos = response.data;
                    response.data.forEach(nodo => {
                        this.drawNode(nodo)
                    });
                })
                .catch(error => {
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
        agregarNodos() {
            let data = {
                'nombre': this.nombre,
                'posicionx': this.x,
                'posiciony': this.y
            }
            axios.post('/api/nodos/', data)
                .then(r => {
                    console.log(r.data);
                    this.draw
                })
                .catch(error => {
                    console.error(error);
                })
        },
        agregarConexion(){
            if (this.nodo_a == this.nodo_b){
                alert('No puede crear una conexion utilizando el mismo nodo')
            }else{
                let data = {
                    "peso":this.peso,
                    "nodo_a":this.nodo_a,
                    "nodo_b":this.nodo_b
                }
                axios.post('/api/conexion/',data)
                .then(rest=>{
                    console.log('Todo Chil');
                    console.log(rest.data);
                })
                .catch(error => {
                    console.error(error);
                })
            }
        },
        
    },
    mounted() {
        var c = document.getElementById("lienzo1")
        this.dialog = document.getElementById("dialog")
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

.cintent-plain {
    display: flex;
    justify-content: center;
    align-items: center;
}
</style>