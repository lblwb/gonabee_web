<?php

function add_sale_modal_to_footer()
{
//    if ( is_checkout() || is_account_page() ) return; // исключаем
    get_template_part('template-parts/modal/modal-sale');
}

add_action('wp_footer', 'add_sale_modal_to_footer');

function sale_vue_modal_script() {
    ?>
    <script>
        const { createApp, ref, onMounted, watch } = Vue

        createApp({
            setup() {
                const showModal = ref(false)
                const email = ref('')

                const closeModal = () => {
                    showModal.value = false
                }

                const subscribe = () => {
                    if (!email.value) return;
                    alert(`Спасибо за подписку, ${email.value}!`)
                    localStorage.setItem('sale_modal_shown', 'true')
                    closeModal()
                }

                // 🔒 Блокировка/разблокировка прокрутки
                watch(showModal, (newVal) => {
                    document.body.style.overflow = newVal ? 'hidden' : ''
                })

                onMounted(() => {
                    if (localStorage.getItem('sale_modal_shown') === 'true') return

                    setTimeout(() => {
                        showModal.value = true
                    }, 72000) // 1.3 минуты
                })

                return {
                    showModal,
                    email,
                    closeModal,
                    subscribe
                }
            }
        }).mount('#sale-modal-app')
    </script>
    <?php
}
add_action('wp_footer', 'sale_vue_modal_script', 100);

