<script>
import HeaderComponent from './Header.vue';
import FooterComponent from './Footer.vue';
export default{
    name: 'ComboComponent',
    components: {
        HeaderComponent,
        FooterComponent
    }
}
</script>

<template>
    <!-- <section class=" flex flex-col min-h-screen "> -->
    <section class=" bg-cover bg-center bg-[url(/public/img/background/page-course.svg)] min-h-screen flex flex-col">
        <HeaderComponent></HeaderComponent>
        <div class="container mx-auto flex-grow">
            <div class="my-[30px]">
                <slot></slot>
            </div>
        </div>
        <FooterComponent></FooterComponent>
    </section>
</template>