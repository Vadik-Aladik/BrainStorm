<script>
import ComboComponent from '@/Layout/Combo.vue'
import { Link } from '@inertiajs/vue3';
import axios from 'axios';
import { route } from 'ziggy-js';
export default{
    data(){
        return{
            course: [],
            page: 1,
            flagLoadCourse: false,
        }
    },
    components:{
        ComboComponent,
        Link
    },
    methods:{
        async getData(){
            const res = await axios.post(`/adminData?page=${this.page}`);
            ++this.page;
            this.course.push(...res.data.resource_course);
            if(res.data.course.next_page_url == null){
                return this.flagLoadCourse = true;
            };
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
            <div  class=" flex flex-col items-center min-[768px]:block">
                <span class=" font-semibold text-xl">Ваши курсы</span>
                <div class="flex flex-wrap mx-5 mt-3">
                    <div class="flex flex-wrap w-[173px] min-[400px]:w-[378px] md:w-full">
                        <Link :href="route('admin.create.course')" class=" w-[173px] h-[173px] rounded-xl bg-red-400 flex flex-col justify-center items-center cursor-pointer mr-4 mb-2 max-[399px]:mr-0">
                            <img src="/public/img/logo/Course.svg" alt="course_create">
                            <h1 class=" font-semibold text-xl text-center text-white mt-3">Добавить новый курс</h1>
                        </Link>
                        <div v-for="elem in course" :key="elem">
                            <Link :href="route('admin.course', elem.course_id)">
                                <div class=" w-[173px] h-[173px] rounded-xl mr-4 p-4 mb-2 max-[399px]:mr-0"
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
                    </div>
                </div>
                <div class=" flex justify-end">
                    <button v-if="!flagLoadCourse" @click.prevent="getData()" class="px-[30px] py-[10px] hover:bg-blue-200 
                hover:text-blue-600 rounded-md my-[10px] text-lg transition ease-in bg-gray-100">Загрузить еще...</button>
                </div>
            </div>

            <div class=" mt-8 flex flex-col items-center min-[768px]:block">
                <span class=" font-semibold text-xl">Другое</span>
                <div class="flex flex-wrap mx-5 mt-3">
                    <div class="flex flex-wrap w-[173px] min-[400px]:w-[378px] md:w-full">
                        <Link :href="route('admin.progress')" class=" w-[173px] h-[173px] rounded-xl bg-yellow-500 flex flex-col justify-center items-center cursor-pointer mr-4 mb-2 max-[399px]:mr-0">
                            <img src="/public/img/logo/Progress.svg" alt="course_create">
                            <h1 class=" font-semibold text-xl text-center text-white mt-3">Прогресс</h1>
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </ComboComponent>
</template>