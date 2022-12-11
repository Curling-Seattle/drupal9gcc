import { AppProps } from "next/app"

import "styles/globals.css"
import "styles/gcc-styles.css"

export default function App({ Component, pageProps }: AppProps) {
  return <Component {...pageProps} />
}
