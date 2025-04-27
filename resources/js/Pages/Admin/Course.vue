<script>
import ComboComponent from "@/Layout/Combo.vue";
import { Link } from "@inertiajs/vue3";
import { route } from "ziggy-js";
export default{
    props:[
        'name',
        'id',
    ],
    components:{
        Link,
        ComboComponent,
    },
    mounted(){
        console.log(this.name);
    }
}
</script>

<template>
    <ComboComponent>
        <div class=" py-[30px] px-[77px] bg-white rounded-xl min-h-[780px]">
            <h1 class=" font-semibold text-2xl text-center">{{ name }}</h1>
            <div class=" overflow-auto h-[678px]">
                <!-- <Link :href="route('admin.create.test')" class=" w-[174px] min-h-16 border-2 border-gray-400 rounded-md p-3 font-semibold cursor-pointer text-lg hover:border-blue-600 hover:bg-blue-300 hover:text-blue-600 transition ease-in">
                    Создать новый тест
                </Link> -->
                <Link :href="route('admin.create.test', id)">
                    <div class=" w-[174px] min-h-16 border-2 border-gray-400 rounded-md p-3 font-semibold cursor-pointer text-lg hover:border-blue-600 hover:bg-blue-300 hover:text-blue-600 transition ease-in">Создать новый тест</div>
                </Link>
                <div class="hidden">
                    <p class=" mt-4 mb-3 text-gray-500">Ваши тесты</p>
                    <div class=" flex flex-wrap">
                        <div class=" w-[174px] min-h-16 border-2 border-gray-400 rounded-md p-3 cursor-pointer ml-5 mb-3 hover:border-blue-600 hover:bg-blue-300 hover:text-blue-600 transition ease-in">
                            <h1 class="font-semibold text-lg">Name_testCourse</h1>
                            <p class=" text-yellow-500 ">Редактировать тест</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </ComboComponent>
</template>