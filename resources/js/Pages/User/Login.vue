<script>
import { router, Link } from '@inertiajs/vue3';
import { ref } from 'vue';
import axios from 'axios';

export default{
    name: 'Login',
    components:{
        Link
    },
    data(){
        return{
            email: '',
            password: '',

            email_error: '',
            password_error: '',

            errors: {},
        }
    },
    methods:{
        async login(){
            const res = await axios.post('/login', {
                email: this.email,
                password: this.password
            }).catch((error)=>{
                this.errors = error.response.data.errors;
            });

            if(res && res.data.login){
                router.visit('/');
            }
            else{
                this.errors.password = ['Неверный пароль'];
            }
        }
    },
}
</script>

<template>
    <section class=" bg-cover bg-center bg-[url(/public/img/background/page-course.svg)] h-screen flex">
        <div class=" bg-white w-[340px] py-[30px] flex flex-col items-center rounded-md mx-auto my-auto min-[320px]:w-[562px]">
            <h1 class=" text-xl font-bold mb-[15px]">Вход</h1>
            <div>
                <input v-model="email" type="email" class=" w-72 h-[30px] px-[10px] focus:outline-none focus:border-2 
                focus:border-blue-800 border-2 border-gray-400 rounded mt-[5px] placeholder:font-light min-[370px]:w-[370px]" placeholder="Введите ваш email">
                <p class=" text-red-600 ml-4 font-light" v-if="errors.email">{{ errors.email[0] }}</p>
            </div>
            <div>
                <input v-model="password" type="password" class=" w-72 h-[30px] px-[10px] focus:outline-none focus:border-2 
                focus:border-blue-800 border-2 border-gray-400 rounded mt-[5px] placeholder:font-light min-[370px]:w-[370px]" placeholder="Введите ваш пароль">
                <p class=" text-red-600 ml-4 font-light" v-if="errors.password">{{ errors.password[0] }}</p>
            </div>
            <button @click.prevent="login()" class=" px-[30px] py-[10px] bg-gray-100 hover:bg-blue-200 hover:text-blue-600 rounded-md my-[20px] text-lg transition ease-in" type="submit">Войти</button>
            <Link :href="route('user.registration')">У меня нет <span class=" text-blue-600">аккаунта</span></Link>
        </div>
    </section>
</template>