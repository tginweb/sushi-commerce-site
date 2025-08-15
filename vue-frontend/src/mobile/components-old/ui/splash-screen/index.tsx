import React from 'react';
import {Image, SafeAreaView, StyleSheet, View} from 'react-native';
import {Easing, useSharedValue,} from 'react-native-reanimated';
import {UiStatusBar} from "~ui/status-bar";
import * as Progress from 'react-native-progress';
import {platform} from "@core/main/lib/platform"
import {Text} from "react-native-ui-lib";
import {wWidth} from "~assets/design";

const duration = 2000;
const easing = Easing.bezier(0.25, -0.5, 0.25, 1);

export const UiSplashScreen = () => {
    const sv = useSharedValue(0);
    const scale = useSharedValue(1);

    const appVersion = platform.getAppVersion()
    const buildVersionName = platform.getBuildVersionName()

    return (
        <SafeAreaView style={styles.container}>
            <View style={styles.inner}>
                <View style={styles.version}>
                    <Text text-sm-m grey30>{appVersion} / {buildVersionName}</Text>
                </View>
                <UiStatusBar containerStyle={styles.statusBar}/>
                <View style={styles.imageContainer}>
                    <Image
                        style={[styles.image]}
                        source={require('~assets/img/splash.png')}
                        resizeMode={'cover'}
                    />
                </View>
                <Progress.CircleSnail
                    style={{
                        position: 'absolute',
                        top: '56%',
                        left: '50%',
                        marginLeft: -18
                    }}
                    thickness={4}
                    size={44}
                    duration={700}
                    color={'#e1865f'}
                    indeterminate={true}
                />
            </View>
        </SafeAreaView>
    );
};

const styles = StyleSheet.create({
    container: {
        flex: 1,
        backgroundColor: '#FFFFFF',
    },
    inner: {
        flex: 1,
    },
    statusBar: {
        position: 'absolute',
        left: 0,
        top: 0,
        width: '100%',
        zIndex: 200
    },
    version: {
        position: 'absolute',
        left: 15,
        bottom: 15,
        zIndex: 200
    },
    imageContainer: {
        justifyContent: 'center',
        alignItems: 'center',
        flex: 1,
    },
    image: {
        flex: 1,
        flexGrow: 1,
        width: wWidth
    },
    textContainer: {
        marginTop: 40,
    },
});

