import Link from "next/link"
import Image from "next/image"

import { PreviewAlert } from "components/preview-alert"

export function Layout({ children }) {
  return (
    <>
      <PreviewAlert />
      <div className="max-w-screen-md px-6 mx-auto">
        <header>
          <div className="container flex items-center justify-between py-6 mx-auto">
            <Link href="/" passHref>
              <a className="hover:bg-gray-100">
	        <Image priority src="/graphics/GCC_Banner2.png"
                       className="header-logo" width={175} height={20}
		       alt="Granite Curling Club of Seattle"
                 />
              </a>
            </Link>
            <Link href="http://localhost:8999" passHref>
              <a target="_blank" rel="external" className="hover:text-blue-600">
                Drupal administration
              </a>
            </Link>
          </div>
        </header>
        <main className="container py-10 mx-auto">{children}</main>
      </div>
    </>
  )
}
