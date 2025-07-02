<script>
import ComboComponent from "@/Layout/Combo.vue"
import { Link } from "@inertiajs/vue3";
import { route } from "ziggy-js";
export default{
    components:{
        ComboComponent,
        Link
    },
    data(){
        return{
            progress: [],
        }
    },
    methods:{
        async getUserProcces(){
            const res = await axios.post('/progressPost');
            this.progress = res.data.user_progress;
        }
    },
    mounted(){
        this.getUserProcces();
    }
}
</script>

<template>
    <ComboComponent>
        <div class=" py-[30px] px-4 min-[510px]:px-[77px] bg-white rounded-xl min-h-[780px]">
            <h1 class=" font-semibold text-2xl text-center mb-3">Ваш прогресс</h1>
            <div v-for="elem in progress" :key="elem" class=" max-md:flex max-md:flex-col max-md:items-center">
                <h1 class=" md:text-xl md:font-semibold mb-2 text-center md:text-left">{{ elem.course_name }}</h1>
                <div class="flex flex-wrap mx-5 mt-3 justify-center min-[450px]:justify-normal min-[450px]:w-[380px] sm:w-full">
                    <div v-for="test in elem.tests" :key="test">
                        <Link :href="route('student.test.view', test.id)">
                            <div v-if="test.score >= 80" class="w-[186px] xl:w-[208px] min-h-[85px] rounded-md border-2 border-gray-400 p-[10px] 
                                        hover:border-green-700 hover:bg-green-200 hover:text-green-700 transition ease-in mr-1 xl:mr-[16px] mb-[10px] cursor-pointer truncate">
                                <span class="text-ellipsis overflow-hidden font-semibold text-xl">{{test.test_name}}</span>
                                <div class="font-semibold text-xl text-green-700 text-end">{{test.score}}%</div>
                            </div>
                        </Link>
                        
                        <Link :href="route('student.test.view', test.id)">
                            <div v-if="test.score >= 35 && test.score < 80" class="w-[186px] xl:w-[208px] min-h-[85px] rounded-md border-2 border-gray-400 p-[10px] 
                                        hover:border-yellow-600 hover:bg-yellow-200 hover:text-yellow-600 transition ease-in mr-1 xl:mr-[16px] mb-[10px] cursor-pointer truncate">
                                <span class="text-ellipsis overflow-hidden font-semibold text-xl">{{test.test_name}}</span>
                                <div class="font-semibold text-xl text-yellow-600 text-end">{{test.score}}%</div>
                            </div>
                        </Link>
                        
                        <Link :href="route('student.test.view', test.id)">
                            <div v-if="test.score <= 35" class="w-[186px] xl:w-[208px] min-h-[85px] rounded-md border-2 border-gray-400 p-[10px] 
                                    hover:border-red-700 hover:bg-red-200 hover:text-red-700 transition ease-in mr-1 xl:mr-[16px] mb-[10px] cursor-pointer truncate">
                                <span class="text-ellipsis overflow-hidden font-semibold text-xl">{{test.test_name}}</span>
                                <div class="font-semibold text-xl text-red-700 text-end">{{test.score}}%</div>
                            </div>
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </ComboComponent>
</template>