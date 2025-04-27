<script>
export default{
    name: 'FooterComponent'
}
</script>

<template>
    <footer class=" rounded-t-md h-[150px] bg-white flex items-center">
        <div class=" container mx-auto">
            <div class="flex justify-between">
                <div class=" text-2xl flex flex-col items-end">
                    <span class=" text-blue-500 text-[32px] font-bold">BRAIN</span>
                    storm
                </div>

                <div class=" flex w-[250px] justify-between">
                    <div class=" flex flex-col items-center">
                        <span class=" font-semibold text-xl text-blue-500">Главная</span>
                        <a href="#">Курсы</a>
                        <a href="#">Прогресс</a>
                    </div>

                    <div class=" flex flex-col items-center">
                        <span class=" font-semibold text-xl text-blue-500">Аккаунт</span>
                        <a href="#">Архив</a>
                        <a href="#">Редактирование</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>
</template>