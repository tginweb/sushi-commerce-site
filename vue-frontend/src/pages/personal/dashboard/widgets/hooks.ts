import {computed, toRefs} from "vue";
import {useRouter} from "vue-router";
import {useQuasar} from "quasar";
import {ICONS} from "@/assets/icons";
import {DefinedProps} from "@/core/vue/types";

export type PersonalWidgetProps = {
    title?: string
    circleNumber?: number
    circleIcon?: any
    circleColor?: any
    colClass?: any
    theme?: any
    fullHeight?: boolean
    link?: any

    headerLink?: any
    headerLinkLabel?: any

    layoutClass?: any
    subtitle?: any
    subtitleLink?: any

    mediaSize?: any
    mediaText?: any
    mediaTextClass?: any

    mediaColor?: string
    mediaIcon?: any
    mediaIconSize?: any
    mediaIconClass?: any
    mediaBorderColor?: any
    mediaBorderThickness?: any
    mediaLeft?: any
    fields?: any
    content?: any
    bottomText?: any
}

export const personalWidgetPropsDefault = {
    mediaBorderColor: 'primary',
    mediaBorderThickness: 0.08,
    fullHeight: true,
    theme: 'mini',
    mediaSize: () => ({
        xs: '35px',
        sm: '40px',
        md: '45px',
        lg: '45px',
        xl: '50px',
    })
}

export function usePersonalProfile(props: DefinedProps<PersonalWidgetProps>) {

    const router = useRouter()
    const $q = useQuasar()

    const {
        colClass,
        theme,
        link,
        headerLink,
        headerLinkLabel,
        fullHeight,
        layoutClass,
        mediaSize,
    } = props

    const {
        mediaText,
    } = toRefs(props)

    const bind = computed(() => {
        const res: any = {
            class: {
                'col-24 col-md-12 col-lg-8': !colClass,
                [colClass]: true,
            },
            style: {}
        }
        if (theme)
            res.class['theme-' + theme] = true
        return res
    })

    const bindInner = computed(() => {
        const res: any = {
            class: {
                'c-inner': true,
                'cursor-pointer': !!link
            },
            style: {}
        }
        if (fullHeight) {
            res.class['self-stretch full-height flex-xl column-xl'] = true
        }
        if (layoutClass) {
            res.class[layoutClass] = true
        } else {
            res.class['q-px-md q-py-md q-px-md-md q-py-md-md q-px-lg-lg q-py-lg-lg'] = true
        }
        return res
    })

    const mediaSizeComp = computed(() => {
        if (typeof mediaSize === 'object') {
            return mediaSize[$q.screen.name]
        } else {
            return mediaSize
        }
    })

    const mediaTextExist = computed(() => {
        return mediaText.value || mediaText.value === 0
    })

    const headerLinkBind = computed(() => {
        const res: any = {
            class: {}
        }

        if (headerLink) {
            res.label = $q.screen.gt.sm ? headerLinkLabel || 'подробнее' : null
            res.to = headerLink
        }

        res.class['c-header-more-link s-font-sm'] = true
        res.noWrap = true
        res.color = 'primary'
        res.iconRight = ICONS.chevronRight
        res.flat = true
        res.dense = true

        return res
    })

    const onClick = () => {

    }

    const onHeaderClick = () => {
        if (headerLink) {
            router.push(headerLink)
        }
    }

    return {
        mediaSizeComp,
        mediaTextExist,
        headerLinkBind,
        bind,
        bindInner,
        onClick,
        onHeaderClick
    }
}
