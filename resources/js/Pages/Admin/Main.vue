<!-- <script>
import ComboComponent from '@/Layout/Combo.vue'
import { Link } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
export default{
    components:{
        ComboComponent,
        Link
    },
    props:[
        'course',
        'resource_course'
    ],
    mounted(){
        console.log(this.course, this.resource_course);
    }
}
</script>

<template>
    <ComboComponent>
        <div class=" py-[30px] px-[77px] bg-white rounded-xl min-h-[780px]">
            <div>
                <span class=" font-semibold text-xl">Ваши курсы</span>
                <div class="flex flex-wrap mx-5 mt-3">
                    <div v-for="elem in resource_course.data" :key="elem">
                        <Link :href="route('admin.course', elem.course_id)">
                            <div class=" w-[173px] h-[173px] rounded-xl mr-4 p-4"
                                :style="{
                                    backgroundImage: `linear-gradient(to top, ${elem.course_color}, transparent), url(${elem.course_img})`,
                                    backgroundSize: 'cover', 
                                    backgroundPosition: 'center', 
                                    backgroundRepeat: 'no-repeat'
                                }">
                                <div class=" flex flex-col items-end mt-[45%] text-white">
                                    <h1 class=" font-semibold text-lg text-center mt-3 w-[100%]"> {{ elem.course_name }}</h1>
                                    <p class=" text-sm mt-[10px]">Открыть>></p>
                                </div>
                            </div>
                        </Link>
                    </div>
                    <Link :href="route('admin.create.course')" class=" w-[173px] h-[173px] rounded-xl bg-red-400 flex flex-col justify-center items-center cursor-pointer mr-4">
                        <img src="/public/img/logo/Course.svg" alt="course_create">
                        <h1 class=" font-semibold text-xl text-center text-white mt-3">Добавить новый курс</h1>
                    </Link>
                </div>
            </div>

            <div class=" mt-8">
                <span class=" font-semibold text-xl">Другое</span>
                <div class="flex flex-wrap mx-5 mt-3">
                    <div class=" w-[173px] h-[173px] rounded-xl bg-green-400 flex flex-col justify-center items-center cursor-pointer mr-4">
                        <img src="/public/img/logo/Student.svg" alt="course_create">
                        <h1 class=" font-semibold text-xl text-center text-white mt-3">Студенты</h1>
                    </div>
                    <div class=" w-[173px] h-[173px] rounded-xl bg-yellow-500 flex flex-col justify-center items-center cursor-pointer mr-4">
                        <img src="/public/img/logo/Progress.svg" alt="course_create">
                        <h1 class=" font-semibold text-xl text-center text-white mt-3">Прогресс</h1>
                    </div>
                </div>
            </div>
        </div>
    </ComboComponent>
</template> -->

<script>
import ComboComponent from '@/Layout/Combo.vue'
import { Link } from '@inertiajs/vue3';
import axios from 'axios';
import { route } from 'ziggy-js';
export default{
    data(){
        return{
            course: null,
        }
    },
    components:{
        ComboComponent,
        Link
    },
    methods:{
        async getData(){
            const res = await axios.post('/adminData');
            console.log(res);
            this.course = res.data.resource_course;
        }
    },
    mounted(){
        this.getData();
    }
}
</script>

<template>
    <ComboComponent>
        <div class=" py-[30px] px-[77px] bg-white rounded-xl min-h-[780px]">
            <div>
                <span class=" font-semibold text-xl">Ваши курсы</span>
                <div class="flex flex-wrap mx-5 mt-3">
                    <div v-for="elem in course" :key="elem">
                        <Link :href="route('admin.course', elem.course_id)">
                            <div class=" w-[173px] h-[173px] rounded-xl mr-4 p-4"
                                :style="{
                                    backgroundImage: `linear-gradient(to top, ${elem.course_color}, transparent), url(${elem.course_img})`,
                                    backgroundSize: 'cover', 
                                    backgroundPosition: 'center', 
                                    backgroundRepeat: 'no-repeat'
                                }">
                                <div class=" flex flex-col items-end mt-[45%] text-white">
                                    <h1 class=" font-semibold text-lg text-center mt-3 w-[100%]"> {{ elem.course_name }}</h1>
                                    <p class=" text-sm mt-[10px]">Открыть>></p>
                                </div>
                            </div>
                        </Link>
                    </div>
                    <Link :href="route('admin.create.course')" class=" w-[173px] h-[173px] rounded-xl bg-red-400 flex flex-col justify-center items-center cursor-pointer mr-4">
                        <img src="/public/img/logo/Course.svg" alt="course_create">
                        <h1 class=" font-semibold text-xl text-center text-white mt-3">Добавить новый курс</h1>
                    </Link>
                </div>
            </div>

            <div class=" mt-8">
                <span class=" font-semibold text-xl">Другое</span>
                <div class="flex flex-wrap mx-5 mt-3">
                    <div class=" w-[173px] h-[173px] rounded-xl bg-green-400 flex flex-col justify-center items-center cursor-pointer mr-4">
                        <img src="/public/img/logo/Student.svg" alt="course_create">
                        <h1 class=" font-semibold text-xl text-center text-white mt-3">Студенты</h1>
                    </div>
                    <div class=" w-[173px] h-[173px] rounded-xl bg-yellow-500 flex flex-col justify-center items-center cursor-pointer mr-4">
                        <img src="/public/img/logo/Progress.svg" alt="course_create">
                        <h1 class=" font-semibold text-xl text-center text-white mt-3">Прогресс</h1>
                    </div>
                </div>
            </div>
        </div>
    </ComboComponent>
</template>