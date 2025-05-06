<script>
import { router, Link } from '@inertiajs/vue3';
import axios from 'axios';
import { route } from 'ziggy-js';
export default{
    components:{
        Link,
    },
    data(){
        return{
            name: '',
            email: '',
            password: '',
            password_confirm: '',

            name_error: '',
            email_error: '',
            password_error: '',
            password_confirm_error: '',
        }
    },
    methods:{
        async registration(){
            const res = await axios.post('/registration', {
                name: this.name,
                email: this.email,
                password: this.password,
                password_confirm: this.password_confirm,
            }).then((res)=>{
                console.log(res);
            }).catch((error)=>{
                console.log(error);
                this.name_error = error.response.data.errors.name;
                this.email_error = error.response.data.errors.email;
                this.password_error = error.response.data.errors.password;
                this.password_confirm_error = error.response.data.errors.password_confirm;
            })

            if(res || res.data.auth){
                router.visit('/');
            }
        }
    }
}
</script>

<template>
    <section class=" bg-cover bg-center bg-[url(/public/img/background/page-course.svg)] h-screen flex justify-center items-center">
        <div class=" bg-white w-[562px] py-[30px] flex flex-col items-center rounded-md">
            <h1 class=" text-xl font-bold mb-[15px]">Регистрация</h1>
            <div>
                <input v-model="name" type="text" class=" w-[370px] h-[30px] px-[10px] focus:outline-none focus:border-2 focus:border-blue-800 border-2 border-gray-400 rounded mt-[5px] placeholder:font-light" placeholder="Введите имя">
                <p class=" text-red-600 ml-4 font-light" v-if="name_error">{{ name_error[0] }}</p>
            </div>
            <div>
                <input v-model="email" type="email" class=" w-[370px] h-[30px] px-[10px] focus:outline-none focus:border-2 focus:border-blue-800 border-2 border-gray-400 rounded mt-[5px] placeholder:font-light" placeholder="Введите email">
                <p class=" text-red-600 ml-4 font-light" v-if="email_error">{{ email_error[0] }}</p>
            </div>
            <div>
                <input v-model="password" type="password" class=" w-[370px] h-[30px] px-[10px] focus:outline-none focus:border-2 focus:border-blue-800 border-2 border-gray-400 rounded mt-[5px] placeholder:font-light" placeholder="Введите пароль">
                <p class=" text-red-600 ml-4 font-light" v-if="password_error">{{ password_error[0] }}</p>
            </div>
            <div>
                <input v-model="password_confirm" type="password" class=" w-[370px] h-[30px] px-[10px] focus:outline-none focus:border-2 focus:border-blue-800 border-2 border-gray-400 rounded mt-[5px] placeholder:font-light" placeholder="Введите пароль повторно">
                <p class=" text-red-600 ml-4 font-light" v-if="password_confirm_error">{{ password_confirm_error[0] }}</p>
            </div>
            <button @click.prevent="registration()" class=" px-[30px] py-[10px] hover:bg-blue-200 hover:text-blue-600 rounded-md my-[10px] text-lg transition ease-in">Зарегестрироваться</button>
            <Link :href="route('user.login')">У меня есть <span class=" text-blue-600">аккаунт</span></Link>
        </div>
    </section>
</template>