import { createI18nInstance } from './src/plugins/i18n/createI18n';
import { config } from "@vue/test-utils";

const i18n = createI18nInstance();
config.global.plugins.push(i18n);