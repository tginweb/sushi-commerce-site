import React from "react"
import {BaseButton, TouchableWithoutFeedback} from "react-native-gesture-handler";
import {BaseButtonProps} from "react-native-gesture-handler/src/components/GestureButtons";
import {Platform, StyleSheet} from "react-native";

export type CardPressableProps = BaseButtonProps & {}

export const CardPressable: React.FC<CardPressableProps> = (props) => {

    const {
        children,
        ...rest
    } = props

    const Wrapper = Platform.OS === "web" ? TouchableWithoutFeedback : BaseButton

    return <Wrapper
        {...rest}
    >
        <>
            {children}
        </>
    </Wrapper>
}

const styles = StyleSheet.create({})

