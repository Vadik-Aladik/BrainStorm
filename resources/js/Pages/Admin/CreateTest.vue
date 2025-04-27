<script>

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
            test_name: '',
            input_error: '',
            quest_error: '',
        }
    }, 
    props:[
        'course_id'
    ],
    methods:{
        changeSelect(test){
            if(test.select == 'input' || test.select == 'textarea'){
                // test.answers = [{answer:'', status: false}];
                test.answers = [{answer: ''}];
            }
        },
        answerChange(testIndex, answerIndex){
            const test = this.tests[testIndex];

            if(test.select == 'radio'){
                test.answers.forEach(answer => {
                    answer.status = false;
                });
            }
        
            test.answers[answerIndex].status = true;
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
            let isValid = true;
            this.tests.forEach((elem, index)=>{
                let emptyAnswer = elem.answers.some(answer => !answer.answer);
                let statusAnswer = elem.answers.some(answer => answer.status);
                if(elem.select == 'radio' || elem.select == 'checkbox'){
                    if(!elem.quest){
                        console.log(`${index} - не введен вопрос`);
                        // this.quest_error = 'не введен вопрос'
                        isValid = false;
                    }
                    if(!elem.select){
                        console.log(`${index} - не выбран тип ответа`);
                        // this.input_error = 'не выбран тип ответа';
                        isValid = false;
                    }
                    if(emptyAnswer){
                        console.log(`${index} - нет вписан вопрос`);
                        // this.input_error = 'нет вписан вопрос';
                        isValid = false;
                    }
                    if(elem.answers.length < 2){
                        console.log(`${index} - минимальное количество вопрос 2`);
                        // this.input_error = 'минимальное количество вопрос - 2';
                        isValid = false;
                    }
                    if(!statusAnswer){
                        console.log(`${index} - не выбран варант ответа`);
                        // this.input_error = 'не выбран варант ответа';
                        isValid = false;
                    }
                }
                else if(elem.select == 'input' || elem.select == 'textarea'){
                    if(!elem.quest){
                        console.log(`${index} - не введен вопрос`);
                        isValid = false;
                    }
                    if(!elem.select){
                        console.log(`${index} - не выбран тип ответа`);
                        isValid = false;
                    }
                }
                else{
                    console.log('не введен тип варианта ответа');
                    isValid = false;
                }
            });

            if(isValid){
                // console.log(this.tests);
                const res = await axios.post(`/admin/new/test/course/${this.course_id}`, {
                    test_name: this.test_name,
                    test: this.tests
                }).catch(error=>{
                    console.log(error);
                });
                console.log(res);
            }
        },
        // warningRed(){
        //     this.test.forEach((elem, index)=>{
        //         if(elem.select == 'input' || elem.select == 'textarea'){

        //         }
        //     });
        // }
    }
}
</script>

<template>
    <section class=" bg-cover bg-center bg-[url(/public/img/background/page-course.svg)] min-h-screen flex justify-center items-center text-lg">
        <div class=" container mx-auto">
            <div class=" bg-white py-[30px] px-[76px] rounded-[10px] min-h-[1020px]">
                <div class=" mb-4">
                    <input v-model="test_name" type="text" class=" w-[406px] h-[40px] pl-4 focus:outline-none focus:border-2 focus:border-blue-800 border-2 border-gray-400 rounded mt-[5px] placeholder:font-light text-blue-600 font-bold" placeholder="Введите название курса">
                    <p class=" text-red-600 text-sm hidden">Внимание! Если вы не введете ответ в поле ввода, вам придется проверять тест студента после его прохождения на вкладке “Проверить” на странице “Прогресс”</p>
                </div>
                <div v-for="(test, index) in tests" :key="index">
                    <div class=" mb-[5px] flex">
                        <span class=" text-blue-600 font-semibold">{{ index+1 }}.</span>
                        <div class=" ml-2">
                            <div class=" mb-[5px]">
                                <input v-model="test.quest" type="text" class=" focus:outline-none focus:border-2 focus:border-blue-800 border-2 border-gray-400 rounded placeholder:font-light w-[363px] h-[30px] pl-[10px]" placeholder="Введите задание" >
                                <select v-model="test.select" @change="changeSelect(test)" name="type_answer" id="" class=" w-44 h-[30px] border border-black rounded font-light ml-5">
                                    <option disabled value="">Выберите тип ответа</option>
                                    <option>radio</option>
                                    <option>checkbox</option>
                                    <option>input</option>
                                    <option>textarea</option>
                                </select>
                            </div>
                            
                            <div v-if="test.select == 'radio'">
                                <div v-for="(answer, answerIndex) in test.answers" :key="answerIndex" >
                                    <div class="mb-[5px]">
                                        <div class="flex items-center">
                                            <input type="radio" :name="'answer' + index" @change="answerChange(index, answerIndex)" :checked="answer.status" :id="'answer' + answerIndex">
                                            <input v-model="answer.answer" type="text" class="focus:outline-none focus:border-2 focus:border-blue-800 border-2 border-gray-400 rounded placeholder:font-light h-[30px] w-[345px] ml-[5px] pl-[10px]" placeholder="Введите вариант ответа">
                                            <button @click.prevent="deleteAnswer(index, answerIndex)" class=" py-[6px] px-[20px] rounded hover:bg-red-200 hover:text-red-600 transition ease-in text-sm ml-5">Убрать</button>
                                        </div>
                                        <!-- <p v-if="input_error">{{ input_error }}</p> -->
                                    </div>
                                </div>
                                <button @click.prevent="pushAnswer(index)" class=" py-[6px] px-[20px] rounded hover:bg-green-200 hover:text-green-600 transition ease-in text-sm ml-5">Добавить ответ</button>
                            </div>
                            <div v-if="test.select == 'checkbox'">
                                <div v-for="(answer, answerIndex) in test.answers" :key="answerIndex" >
                                    <div class="mb-[5px]">
                                        <input type="checkbox" @change="answerChange(index, answerIndex)" :checked="answer.status" :id="'test'+answerIndex">
                                        <input v-model="answer.answer" type="text" class="focus:outline-none focus:border-2 focus:border-blue-800 border-2 border-gray-400 rounded placeholder:font-light h-[30px] w-[345px] ml-[5px] pl-[10px]" placeholder="Введите вариант ответа">
                                        <button @click.prevent="deleteAnswer(index, answerIndex)" class=" py-[6px] px-[20px] rounded hover:bg-red-200 hover:text-red-600 transition ease-in text-sm ml-5">Убрать</button>
                                    </div>
                                </div>
                                <button @click.prevent="pushAnswer(index)" class=" py-[6px] px-[20px] rounded hover:bg-green-200 hover:text-green-600 transition ease-in text-sm ml-5">Добавить ответ</button>
                            </div>

                            <div v-if="test.select == 'input'">
                                <input v-model="test.answers[0].answer" type="text" class="focus:outline-none focus:border-2 focus:border-blue-800 border-2 border-gray-400 rounded placeholder:font-light h-[30px] w-[363px] pl-[10px]" placeholder="Введите ответ">
                            </div>

                            <div v-if="test.select == 'textarea'">
                                <textarea v-model="test.answers[0].answer" name="" id="" class="focus:outline-none focus:border-2 focus:border-blue-800 border-2 border-gray-400 rounded placeholder:font-light h-[150px] w-[363px] pl-[10px] resize-none" placeholder="Введите ответ"></textarea>
                            </div>
                        </div>
                        
                    </div>

                    
                </div>

                <div mb-5>
                    <span class=" text-blue-400">{{ tests.length+1 }}.</span>
                    <button @click.prevent="pushTest" class="py-[6px] px-[20px] rounded hover:bg-blue-200 hover:text-blue-600 transition ease-in text-sm ml-5">Добавить вопрос</button>
                </div>

                <div class=" mt-[30px] flex justify-between">
                    <button class="py-[6px] px-[20px] rounded hover:bg-blue-200 hover:text-blue-600 transition ease-in ml-5 text-lg">Вернуться назад</button>
                    <button class=" py-[6px] px-[20px] rounded hover:bg-green-200 hover:text-green-600 transition ease-in ml-5 text-lg" @click.prevent="createTest">Создать курс</button>
                </div>
            </div>
        </div>
    </section>
</template>


<!-- <script>
export default{
    data(){
        return {
            selected: '',
            tests: [{
                quest: '',
                select: '',
                answers: [
                    {
                        answer:'', status: ''
                    }
                ]
            }],
        }
    },
}
</script>

<template>
    <section class=" bg-cover bg-center bg-[url(/public/img/background/page-course.svg)] h-screen flex justify-center items-center">
        <div class=" container mx-auto">
            <div class=" bg-white py-[30px] px-[76px] rounded-[10px] min-h-[1020px]">
                <div class=" mb-4">
                    <input type="text" class=" w-[406px] h-[40px] pl-4 focus:outline-none focus:border-2 focus:border-blue-800 border-2 border-gray-400 rounded mt-[5px] placeholder:font-light text-blue-600 font-bold" placeholder="Введите название курса">
                    <p class=" text-red-600 text-sm hidden">Внимание! Если вы не введете ответ в поле ввода, вам придется проверять тест студента после его прохождения на вкладке “Проверить” на странице “Прогресс”</p>
                </div>
                
                <div class="mb-5">
                    <div class=" mb-[5px]">
                        <span class=" text-blue-600 font-semibold">1.</span>
                        <input type="text" class=" ml-2 focus:outline-none focus:border-2 focus:border-blue-800 border-2 border-gray-400 rounded placeholder:font-light w-[366px] h-[30px] pl-[10px]" placeholder="Введите задание" >
                        <select v-model="selected" name="type_answer" id="" class=" w-44 h-[30px] border border-black rounded font-light ml-5">
                            <option disabled value="">Выберите тип ответа</option>
                            <option value="radiobutton">radiobutton</option>
                            <option value="checkbox">checkbox</option>
                            <option value="input">input</option>
                            <option value="textarea">textarea</option>
                        </select>
                    </div>

                    <div v-if="selected == 'radiobutton'">
                        <div class=" ml-5">
                            <div class="mb-[5px]">
                                <input type="radio" name="test1" value="answer1" id="">
                                <input type="text" class="focus:outline-none focus:border-2 focus:border-blue-800 border-2 border-gray-400 rounded placeholder:font-light h-[30px] w-[345px] ml-[5px] pl-[10px]" placeholder="Введите вариант ответа">
                                <button class=" py-[6px] px-[20px] rounded hover:bg-red-200 hover:text-red-600 transition ease-in text-sm ml-5">Убрать</button>
                            </div>
                            <div class="mb-[5px]">
                                <input type="radio" name="test1" value="answer2" id="">
                                <input type="text" class="focus:outline-none focus:border-2 focus:border-blue-800 border-2 border-gray-400 rounded placeholder:font-light h-[30px] w-[345px] ml-[5px] pl-[10px]" placeholder="Введите вариант ответа">
                                <button class=" py-[6px] px-[20px] rounded hover:bg-red-200 hover:text-red-600 transition ease-in text-sm ml-5">Убрать</button>
                            </div>
                            <div class="mb-[5px]">
                                <input type="radio" name="test1" value="answer3" id="">
                                <input type="text" class="focus:outline-none focus:border-2 focus:border-blue-800 border-2 border-gray-400 rounded placeholder:font-light h-[30px] w-[345px] ml-[5px] pl-[10px]" placeholder="Введите вариант ответа">
                                <button class=" py-[6px] px-[20px] rounded hover:bg-red-200 hover:text-red-600 transition ease-in text-sm ml-5">Убрать</button>
                            </div>
                            <button class=" py-[6px] px-[20px] rounded hover:bg-green-200 hover:text-green-600 transition ease-in text-sm ml-5">Добавить ответ</button>
                        </div>
                    </div>
                    
                    <div v-if="selected == 'checkbox'">
                        <div class=" ml-5">
                            <div class="mb-[5px]">
                                <input type="checkbox" value="answer1" id="test1">
                                <input type="text" class="focus:outline-none focus:border-2 focus:border-blue-800 border-2 border-gray-400 rounded placeholder:font-light h-[30px] w-[345px] ml-[5px] pl-[10px]" placeholder="Введите вариант ответа">
                                <button class=" py-[6px] px-[20px] rounded hover:bg-red-200 hover:text-red-600 transition ease-in text-sm ml-5">Убрать</button>
                            </div>
                            <div class="mb-[5px]">
                                <input type="checkbox" value="answer2" id="test1">
                                <input type="text" class="focus:outline-none focus:border-2 focus:border-blue-800 border-2 border-gray-400 rounded placeholder:font-light h-[30px] w-[345px] ml-[5px] pl-[10px]" placeholder="Введите вариант ответа">
                                <button class=" py-[6px] px-[20px] rounded hover:bg-red-200 hover:text-red-600 transition ease-in text-sm ml-5">Убрать</button>
                            </div>
                            <div class="mb-[5px]">
                                <input type="checkbox" value="answer3" id="test1">
                                <input type="text" class="focus:outline-none focus:border-2 focus:border-blue-800 border-2 border-gray-400 rounded placeholder:font-light h-[30px] w-[345px] ml-[5px] pl-[10px]" placeholder="Введите вариант ответа">
                                <button class=" py-[6px] px-[20px] rounded hover:bg-red-200 hover:text-red-600 transition ease-in text-sm ml-5">Убрать</button>
                            </div>
                            <button class=" py-[6px] px-[20px] rounded hover:bg-green-200 hover:text-green-600 transition ease-in text-sm ml-5">Добавить ответ</button>
                        </div>
                    </div>

                    <div v-if="selected == 'input'">
                        <input type="text" class="focus:outline-none focus:border-2 focus:border-blue-800 border-2 border-gray-400 rounded placeholder:font-light h-[30px] w-[366px] ml-[18px] pl-[10px]" placeholder="Введите ответ">
                    </div>

                    <div v-if="selected == 'textarea'">
                        <textarea name="" id="" class="focus:outline-none focus:border-2 focus:border-blue-800 border-2 border-gray-400 rounded placeholder:font-light h-[150px] w-[366px] ml-[18px] pl-[10px] resize-none" placeholder="Введите ответ"></textarea>
                    </div>
                </div>

                <div mb-5>
                    <span class=" text-blue-400">2.</span>
                    <button class="py-[6px] px-[20px] rounded hover:bg-blue-200 hover:text-blue-600 transition ease-in text-sm ml-5">Добавить вопрос</button>
                </div>
            </div>
        </div>
    </section>
</template>
 -->