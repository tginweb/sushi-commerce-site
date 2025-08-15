import React, {useEffect} from "react"
import {useStores} from "~stores"
import {observer} from "mobx-react"
import {OrderInformer} from "~com/sale/order/order-informer";
import {usePathname} from "expo-router"

type TProps = {}

export const SaleBus: React.FC<TProps> = observer(({}) => {

    const {sale, ui} = useStores()
    const routePath = usePathname()



    return <></>
})

