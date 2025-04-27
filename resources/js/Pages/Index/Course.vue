<script>
import ComboComponent from "@/Layout/Combo.vue";
import { Link } from "@inertiajs/vue3";
import { route } from "ziggy-js";
export default{
    components:{
        Link,
        ComboComponent,
    },
    props:[
        'course_admin',
    ],  
    mounted(){
        console.log(this.course_admin);
    }
}
</script>

<template>
    <ComboComponent>
        <div class=" py-[30px] px-[77px] bg-white rounded-xl min-h-[780px]">
            <h1 class=" font-semibold text-2xl text-center">{{course_admin[0].course_name}}</h1>
            <div class=" overflow-auto h-[678px] mt-5">
                <div class="flex flex-wrap">
                    <div v-for="elem in course_admin[0].course" :key="elem">
                        <Link :href="route('student.test', [course_admin[0].id, elem.id])">
                            <div class=" w-[205px] min-h-16 border-2 border-gray-400 rounded-md p-3 cursor-pointer ml-5 mb-3 hover:border-blue-600 hover:bg-blue-300 hover:text-blue-600 transition ease-in">
                                <h1 class="font-semibold text-lg truncate ">{{ elem.test_name }}</h1>
                                <p class=" text-green-600 ">Пройти тест{{ elem.id }}</p>
                            </div>
                        </Link>
                    </div>
                    
                </div>
            </div>
        </div>
    </ComboComponent>
</template>