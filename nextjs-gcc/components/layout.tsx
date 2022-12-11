import { Navbar } from "./navbar"
import { Footer } from "./footer"

import { PreviewAlert } from "components/preview-alert"

export function Layout({ children }) {
  return (
    <>
      <PreviewAlert />
      <div className="max-w-screen-md px-6 mx-auto">
        <Navbar />
        <main className="container py-10 mx-auto">{children}</main>
        <Footer />
      </div>
    </>
  )
}
