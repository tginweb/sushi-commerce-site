import React, {useCallback, useEffect, useState} from "react"
import {StyleSheet, ViewStyle} from "react-native"
import {Colors, Text, TouchableOpacity, View, ViewProps} from "react-native-ui-lib"
import {ProductModel} from "@core/catalog/model/Product"
import cleanText from "@core/main/util/react-native/cleanText"
import {UiImage} from "~ui/image";
import {wWidth} from "~assets/design";

type TProps = ViewProps & {
    containerStyle?: ViewStyle
    entity: ProductModel
    expandable?: boolean
    expanded?: boolean
    onPressDetail?: (entity: ProductModel) => void
    onExpand?: () => void
}

export const ProductSostavDetail: React.FC<TProps> = (
    {
        containerStyle = {},
        entity,
        expandable = false,
        expanded = false,
        onPressDetail,
        onExpand,
        ...rest
    }
) => {
    const [expandedState, setExpandedState] = useState(expanded)

    useEffect(() => {
        setExpandedState(expanded)
    }, [expanded])

    const onPressItem = useCallback((itemModel: ProductModel) => {
        if (!expandedState) {
            const newState = !expandedState
            setExpandedState(newState)
            onExpand && onExpand()
        } else {
            onPressDetail && onPressDetail(itemModel)
        }
    }, [onExpand, onPressDetail, expandedState])

    if (!entity.sotavRolls.length)
        return <></>

    const inner = entity.sotavRolls.map((item, index) => {

        const itemContent = expandedState ? (
            <View
                style={[styles.label, styles.labelExpanded]}
                paddingH-9
                paddingV-5
                row
                gap-4
            >
                <View flexS gap-3>
                    <Text text-sm-m numberOfLines={1}>{item.NAME}</Text>
                    <Text text-xs-lh2 grey30>{cleanText(item.PREVIEW_TEXT)}</Text>
                </View>
                <View marginL-auto>
                    <UiImage
                        style={{
                            width: 65,
                            height: 65,
                        }}
                        contentFit={'contain'}
                        source={{uri: item.imageSrcRequired}}
                    />
                </View>
            </View>
        ) : (
            <View
                style={[styles.label]}
                paddingH-7
                paddingV-2
                row
                gap-3
                centerV
            >
                <UiImage
                    style={{
                        width: 27,
                        height: 27,
                    }}
                    contentFit={'contain'}
                    source={{uri: item.imageSrcRequired}}
                />
                <Text
                    text-xs
                    grey20
                    numberOfLines={1}
                    style={{maxWidth: wWidth - 70}}
                >{item.NAME}</Text>
            </View>
        )

        return <TouchableOpacity key={item.ID} onPress={() => onPressItem(item)}>{itemContent}</TouchableOpacity>
    })

    return <View
        row
        style={[styles.container, containerStyle]}
        {...rest}
    >
        {inner}
    </View>
}

const styles = StyleSheet.create({
    container: {
        flexWrap: 'wrap',
        gap: 8,
        //borderWidth: 1
    },
    containerExpanded: {
        gap: 10
    },
    label: {
        borderColor: Colors.grey50,
        borderWidth: 1,
        borderRadius: 10,
        overflow: 'hidden'
    },
    labelExpanded: {
        width: '100%',
    },
})

