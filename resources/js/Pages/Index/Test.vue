<script>
import axios from 'axios';

export default{
    props:[
        'id_course',
        'id_test',
    ],
    data(){
        return{
            radio: [],
            checkbox: [],
            input: [],
            textarea: [],
            test: [],
            count: 0,
        }
    },
    methods:{
        checkUserTest(){
            // console.log(this.radio);
            // console.log(this.checkbox);
            // console.log(this.input);
            // console.log(this.textarea);
            
            this.radio.forEach(element => {
                if(element.status){
                    this.count++;
                }
            });
            this.checkbox.forEach(element => {
                if(element.status){
                    this.count++;
                }
            });

            console.log(this.count);
            this.count = 0;
        },
        async testGet(){
            const res = await axios.post(`/test/${this.id_test}/get`);
            this.test = res.data.test[0];
            console.log(this.test);
        }
    },
    mounted(){
        this.testGet();
    }
}
</script>

<template>
    <div class=" py-[30px] bg-cover bg-center bg-[url(/public/img/background/page-course.svg)] min-h-screen flex justify-center items-center text-lg">
        <div class=" container mx-auto">
            <div class=" bg-white py-[30px] px-[76px] rounded-[10px] min-h-[1020px]">
                <h1 class=" text-2xl text-blue-600 font-semibold mb-[30px]">{{test.test_name}}</h1>

                <div v-for="(quest, key) in test.quest" :key="key" class=" flex mb-5">
                    <span class=" font-semibold text-blue-600">{{ key+1 }}.</span>
                    <div class="ml-2">
                        <div class="font-semibold">{{quest.quest_text}}</div>
                        <div v-for="(questAnswer, keyAnswer) in quest.answer" :key="keyAnswer">
                            <div v-if="questAnswer.type == 'radio'">
                                <div class=" flex items-center mb-1">
                                    <!-- <input type="radio" :name="'text'+key" :value="questAnswer.status" id=""> -->
                                    <input type="radio" :name="'text'+key" :value="{'quest':key, 'answer': keyAnswer,'status':questAnswer.status}" v-model="radio[key]" id="">
                                    <label class=" ml-2" for="">{{ questAnswer.answer_text }}</label>
                                </div>
                            </div>

                            <div v-if="questAnswer.type == 'checkbox'">
                                <div class=" flex items-center mb-1">
                                    <!-- <input type="checkbox" :name="'text'+key" :value="questAnswer.status" id=""> -->
                                    <input type="checkbox" :name="'text'+key" :value="{'quest':key, 'answer': keyAnswer,'status':questAnswer.status}" v-model="checkbox" id="">
                                    <label class=" ml-2" for="">{{ questAnswer.answer_text }}</label>
                                </div>
                            </div>

                            <div v-if="questAnswer.type == 'input'">
                                <input v-model="input[key]" type="text" class=" mt-1 focus:outline-none focus:border-2 focus:border-blue-800 border-2 border-gray-400 rounded placeholder:font-light h-[30px] w-[363px] pl-[10px]" :name="'text'+key" id="" placeholder="Введите ответ">
                            </div>

                            <div v-if="questAnswer.type == 'textarea'">
                                <textarea v-model="textarea[key]" class=" mt-1 focus:outline-none focus:border-2 focus:border-blue-800 border-2 border-gray-400 rounded placeholder:font-light h-[150px] w-[363px] pl-[10px] resize-none" :name="'text'+key" id="" placeholder="Введите ответ">
                                </div>></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class=" flex justify-end">
                    <button class=" py-[6px] px-[20px] rounded hover:bg-green-200 hover:text-green-600 transition ease-in ml-5 text-lg" @click.prevent="checkUserTest">Завершить тест</button>
                </div>
            </div>
        </div>
    </div>
</template>
