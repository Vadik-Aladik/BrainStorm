<script>
import { Link, router } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
export default{
    data(){
        return {
            inputSelect: '',
            flagRed: false,
            tests: [{
                    quest: '',
                    select: '',
                    answers: [
                        {
                            answer:'', status: false
                        },
                    ]
                }],
            errors: [],
            test_name: '',
            input_error: '',
            quest_error: '',
        }
    }, 
    props:[
        'course_id'
    ],
    components:{
        Link
    },
    methods:{
        changeSelect(test, index){
            if(test.select == 'input' || test.select == 'textarea'){
                // test.answers = [{answer:'', status: false}];
                test.answers = [{answer: ''}];
            }
            if(test.select == "Delete"){
                this.tests.splice(index,1);
            }
        },
        answerChange(testIndex, answerIndex){
            const test = this.tests[testIndex];

            if(test.select == 'radio'){
                test.answers.forEach(answer => {
                    answer.status = false;
                });
                test.answers[answerIndex].status = true;
            }

            if(test.select == 'checkbox'){
                test.answers[answerIndex].status = !test.answers[answerIndex].status;
            }
        },
        pushTest(){
            this.tests.push({
                    quest: '',
                    select: '',
                    answers: [
                        {
                            answer:'', status: false
                        },
                    ]
                });
        },
        pushAnswer(testIndex){
            const test = this.tests[testIndex];

            test.answers.push({
                answer:'', status: false
            });
        },
        deleteAnswer(testIndex, answerIndex){
            const test = this.tests[testIndex];
            test.answers.splice(answerIndex, 1);
        },
        async createTest(){
            this.errors = [];
            let isValid = true;
            this.tests.forEach((elem, index)=>{
                let emptyAnswer = elem.answers.some(answer => !answer.answer);
                let statusAnswer = elem.answers.some(answer => answer.status);
                if(elem.select == 'radio' || elem.select == 'checkbox'){
                    if(!elem.quest){
                        this.errors[index+1] = `${index+1} - не введен текст вопроса`;
                        isValid = false;
                    }
                    if(!elem.select){
                        this.errors[index+1] = `${index+1} - не выбран тип ответа`;
                        isValid = false;
                    }
                    if(emptyAnswer){
                        this.errors[index+1] = `${index+1} - не вписан текст вопрос`;
                        isValid = false;
                    }
                    if(elem.answers.length < 2){
                        this.errors[index+1] = `${index+1} - минимальное количество вопрос - 2`;
                        isValid = false;
                    }
                    if(!statusAnswer){
                        this.errors[index+1] = `${index+1} - не выбран варант ответа`;
                        isValid = false;
                    }
                }
                else if(elem.select == 'input' || elem.select == 'textarea'){
                    if(!elem.quest){
                        this.errors[index+1] = `${index+1} - не введен текст вопроса`;
                        isValid = false;
                    }
                    if(!elem.select){
                        this.errors[index+1] = `${index+1} - не выбран тип ответа`;
                        isValid = false;
                    }
                }
                else{
                    this.errors[index+1] = `${index+1} - не выбран тип варианта вопроса`;
                    isValid = false;
                }
            });

            if(this.test_name == ''){
                this.errors[0] = `название теста не написано`;
                isValid = false;
            }

            if(isValid){
                this.tests.forEach(elem => {
                    if(elem.select == 'input' && elem.answers[0].answer){
                        elem.answers[0].answer = elem.answers[0].answer.toLowerCase().replace(/\s+/g, ' ').trim();
                    }
                })
                const res = await axios.post(`/admin/new/test/course/${this.course_id}`, {
                    test_name: this.test_name,
                    test: this.tests
                });
                
                if(res || res.data.result){
                    router.visit('/admin');
                }
            }
        },
    }
}
</script>

<template>
    <section class=" bg-cover bg-center bg-[url(/public/img/background/page-course.svg)] min-h-screen flex justify-center items-center text-lg">
        <div class=" container mx-auto">
            <!-- <div class="bg-white py-[30px] px-[76px] rounded-[10px] min-h-[1020px] max-sm:px-5 my-5 max-md:flex max-md:flex-col max-md:items-center"> -->
            <div class="bg-white py-[30px] px-[76px] rounded-[10px] min-h-[100px] max-sm:px-5 my-5 max-md:flex max-md:flex-col max-md:items-center">
                <div>
                    <div class=" mb-4 ">
                        <input v-model="test_name" type="text" class=" w-[382px] max-[400px]:w-72 h-[40px] pl-4 focus:outline-none focus:border-2 focus:border-blue-800 border-2 border-gray-400 rounded mt-[5px] placeholder:font-light text-blue-600 font-bold" placeholder="Введите название курса">
                        <!-- <p class=" text-red-600 text-sm hidden">Внимание! Если вы не введете ответ в поле ввода, вам придется проверять тест студента после его прохождения на вкладке “Проверить” на странице “Прогресс”</p> -->
                        <div v-if="errors.length != 0" class=" text-red-600 text-sm border border-red-700 bg-red-200 w-[320px] p-4 rounded-md my-2 font-medium">
                            <div class="min-w-1" v-for="elem in errors" :key="elem">
                                {{ elem }}
                            </div>
                        </div>
                    </div>
                    <div v-for="(test, index) in tests" :key="index" class="max-md:mt-5">
                        <div class=" mb-[5px] flex max-md:mt-6">
                            <span class=" text-blue-600 font-semibold">{{ index+1 }}.</span>
                            <div class=" ml-2">
                                <div class=" mb-[5px] max-md:flex max-md:flex-col max-md:items-end max-md:w-[363px] max-[400px]:w-72">
                                    <input v-model="test.quest" type="text" class=" focus:outline-none focus:border-2 focus:border-blue-800 border-2 border-gray-400 rounded placeholder:font-light w-[363px] max-[400px]:w-72 h-[30px] pl-[10px]" placeholder="Введите задание" >
                                    <select v-model="test.select" @change="changeSelect(test, index)" name="type_answer" id="" class=" w-44 h-[30px] border border-black rounded font-light ml-5 max-md:my-2">
                                        <option disabled value="">Выберите тип ответа</option>
                                        <option>radio</option>
                                        <option>checkbox</option>
                                        <option>input</option>
                                        <option>textarea</option>
                                        <option class=" text-red-600 font-semibold">Delete</option>
                                    </select>
                                </div>
                                
                                <div v-if="test.select == 'radio'">
                                    <div v-for="(answer, answerIndex) in test.answers" :key="answerIndex" >
                                        <div class="mb-[5px] flex max-md:flex-col max-md:items-end">
                                            <div>
                                                <input type="radio" :name="'answer' + index" @change="answerChange(index, answerIndex)" :checked="answer.status" :id="'answer' + answerIndex">
                                                <input v-model="answer.answer" type="text" class="focus:outline-none focus:border-2 focus:border-blue-800 border-2 border-gray-400 rounded placeholder:font-light h-[30px] w-[345px] max-[400px]:w-[270px] ml-[5px] pl-[10px]" placeholder="Введите вариант ответа">
                                            </div>
                                            <button @click.prevent="deleteAnswer(index, answerIndex)" class=" py-[6px] px-[20px] rounded hover:bg-red-200 hover:text-red-600 transition ease-in text-sm ml-5 bg-gray-100 max-md:my-2">Убрать</button>
                                        </div>
                                        <!-- <p v-if="input_error">{{ input_error }}</p> -->
                                    </div>
                                    <button @click.prevent="pushAnswer(index)" class=" py-[6px] px-[20px] rounded hover:bg-green-200 hover:text-green-600 transition ease-in text-sm ml-5 bg-gray-100">Добавить ответ</button>
                                </div>
                                <div v-if="test.select == 'checkbox'">
                                    <div v-for="(answer, answerIndex) in test.answers" :key="answerIndex" >
                                        <div class="mb-[5px] flex max-md:flex-col max-md:items-end">
                                            <div>
                                                <input type="checkbox" @change="answerChange(index, answerIndex)" :checked="answer.status" :id="'test'+answerIndex">
                                                <input v-model="answer.answer" type="text" class="focus:outline-none focus:border-2 focus:border-blue-800 border-2 border-gray-400 rounded placeholder:font-light h-[30px] w-[345px] max-[400px]:w-[270px] ml-[5px] pl-[10px]" placeholder="Введите вариант ответа">
                                            </div>
                                            <button @click.prevent="deleteAnswer(index, answerIndex)" class=" py-[6px] px-[20px] rounded hover:bg-red-200 hover:text-red-600 transition ease-in text-sm ml-5 bg-gray-100 max-md:my-2">Убрать</button>
                                        </div>
                                    </div>
                                    <button @click.prevent="pushAnswer(index)" class=" py-[6px] px-[20px] rounded hover:bg-green-200 hover:text-green-600 transition ease-in text-sm ml-5 bg-gray-100">Добавить ответ</button>
                                </div>

                                <div v-if="test.select == 'input'">
                                    <input v-model="test.answers[0].answer" type="text" class="focus:outline-none focus:border-2 focus:border-blue-800 border-2 border-gray-400 rounded placeholder:font-light h-[30px] w-[363px] max-[400px]:w-72 pl-[10px]" placeholder="Введите ответ">
                                </div>

                                <div v-if="test.select == 'textarea'">
                                    <textarea v-model="test.answers[0].answer" name="" id="" class="focus:outline-none focus:border-2 focus:border-blue-800 border-2 border-gray-400 rounded placeholder:font-light h-[150px] w-[363px] max-[400px]:w-72 pl-[10px] resize-none" placeholder="Введите ответ"></textarea>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                
                    <div mb-5>
                        <span class=" text-blue-400">{{ tests.length+1 }}.</span>
                        <button @click.prevent="pushTest" class="py-[6px] px-[20px] rounded hover:bg-blue-200 hover:text-blue-600 transition ease-in text-sm ml-5">Добавить вопрос</button>
                    </div>
                </div>

                <div class=" mt-[30px] flex justify-between max-md:flex-col-reverse">
                    <Link :href="route('admin.main')" class="py-[6px] px-[20px] rounded hover:bg-blue-200 hover:text-blue-600 transition ease-in ml-5 text-lg bg-gray-100 max-md:my-5">Вернуться назад</Link>
                    <button class=" py-[6px] px-[20px] rounded hover:bg-green-200 hover:text-green-600 transition ease-in ml-5 text-lg bg-gray-100" @click.prevent="createTest">Создать тест</button>
                </div>
            </div>
        </div>
    </section>
</template>
