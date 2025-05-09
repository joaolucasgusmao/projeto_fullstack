import type { Metadata } from "next";
import "@/styles/globals.css";

import "@fontsource/roboto/300.css";
import "@fontsource/roboto/400.css";
import "@fontsource/roboto/500.css";
import "@fontsource/roboto/700.css";
import { UserProvider } from "@/context/UserContext";

export const metadata: Metadata = {
  title: "Portal de Notícias",
};

const RootLayout = ({
  children,
}: Readonly<{
  children: React.ReactNode;
}>) => {
  return (
    <UserProvider>
      <html lang="pt-BR">
        <body>{children}</body>
      </html>
    </UserProvider>
  );
};

export default RootLayout;
