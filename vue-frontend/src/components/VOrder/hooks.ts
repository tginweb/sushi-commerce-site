import {onBeforeUnmount, onMounted, ref} from "vue";
import {QForm} from "quasar";
import {useVorderStore} from "src/modules/shop/store/vorder";
import {storeToRefs} from "pinia";
import {useBus} from "@/core/store/bus";
import {useGraphql} from "@/core/graphql/service";
import {useShopStore} from "@/modules/shop/store/shop";

export function useVorderComponent() {

    const {bus} = useBus()

    const form = ref<QForm>()

    const shopStore = useShopStore()
    const {} = storeToRefs(shopStore)

    const vorderStore = useVorderStore()

    const {
        attrs,
    } = storeToRefs(vorderStore)

    const scrollToErrors = () => {

        const errorElm = form.value?.$el.querySelector('.q-field--error')

        if (errorElm) {
            errorElm.scrollIntoView({block: 'start'})
            return;
        }
    }

    const validate = async () => {
        if (!form.value)
            return false
        try {
            const res = await form.value?.validate(true)
            if (!res) {
                scrollToErrors()
            }
            return res
        } catch (e) {
            console.log('e3', e)
            console.log(e)
            scrollToErrors()
            return false
        }
    }


    onMounted(() => {
        bus.on('vorder:validate', validate)
    })

    onBeforeUnmount(() => {
        bus.off('vorder:validate', validate)
    })

    const {mutationWrapped} = useGraphql()

    const onMutate = async () => {

        /*
        const res = await mutationWrapped(
            userPubLoginRequestMutation({
                sid: true,
                state: {
                    __fragment: 'ResponseState'
                }
            }, {
                phone: '79500000000',
                confirmMode: 'call'
            })
        )

         */

        console.log('mutationWrapped')
    }

    return {
        form,
        validate,
        scrollToErrors,
        onMutate,
        attrs
    }
}
