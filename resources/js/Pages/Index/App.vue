<script>
import ComboComponent from "@/Layout/Combo.vue"
import { Link } from "@inertiajs/vue3";
import { route } from "ziggy-js";

export default{
    components:{
        Link,
        ComboComponent
    },
    props:[
        'courses'
    ],
    mounted(){
        console.log(this.courses);
    }
}
</script>

<template>
    <ComboComponent>
        <div class=" py-[30px] px-[77px] bg-white rounded-xl min-h-[780px]">
            <span class=" font-semibold text-xl">Ваши курсы</span>
                <div class="flex flex-wrap mx-5 mt-3">
                    <div v-for="elem in courses.data" :key="elem">
                        <Link :href="route('student.course', elem.course_id)">
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
                </div>
        </div>
    </ComboComponent>
</template>