<div id="sale-modal-app" v-cloak>
    <div v-if="showModal" class="modal" @click.self="closeModal" :class="{__show: showModal}">
        <div class="modal-content">
            <span @click="closeModal" class="close-btn" style="position: absolute; top: 10px; right: 15px; cursor: pointer; font-size: 14px">×</span>
            <h2>Получите 10% скидки на первую покупку!</h2>
            <p>Подпишитесь на нашу рассылку и получите скидку 10% на первую покупку.</p>
            <form @submit.prevent="subscribe">
                <input type="email" v-model="email" placeholder="Ваш email" required />
                <button type="submit">Подписаться</button>
            </form>
        </div>
    </div>
</div>
