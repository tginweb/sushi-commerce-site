import React from "react"
import {StyleSheet} from "react-native"
import {Text, View} from "react-native-ui-lib";

export type SiteHeaderProps = {}

export const SiteHeader: React.FC<SiteHeaderProps> = (props) => {

    const {

        ...rest
    } = props

    return <View>
        <Text>HEADER</Text>
    </View>
}

const styles = StyleSheet.create({})
