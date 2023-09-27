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
</template>

<script>
export default {
    data() {
        return {
            
        }
    },
    methods: {
        
    },
    mounted() {
        
    },
};
</script>

<style scoped>
.cintent-plain {
    display: flex;
    justify-content: center;
    align-items: center;
}
</style>
