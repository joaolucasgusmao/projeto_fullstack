"use client";

import { useDashboardLayout } from "@/context/DashboardLayoutContext";
import { useUser } from "@/context/UserContext";
import { useMediaQuery, useTheme, Box } from "@mui/material";
import HeaderComponent from "@/components/Dashboard/HeaderComponent";
import SidebarIconComponent from "@/components/Dashboard/Sidebar/SidebarIconComponent";
import UserMenuBoxComponent from "@/components/Dashboard/UserMenu/UserMenuBoxComponent";
import UserMenuComponent from "@/components/Dashboard/UserMenu/UserMenuComponent";
import SidebarComponent from "@/components/Dashboard/Sidebar/SidebarComponent";
import { ReactNode } from "react";

interface DashboardLayoutProps {
  children: ReactNode;
  useSidebar?: boolean;
}

const DashboardLayout: React.FC<DashboardLayoutProps> = ({
  children,
  useSidebar,
}) => {
  const { user } = useUser();
  const {
    open,
    openNews,
    openBanners,
    openUsers,
    openUserMenu,
    anchorEl,
    handleToggleMenu,
    handleToggleUserMenu,
    handleClose,
    handleLogout,
    setOpen,
    setOpenBanners,
    setOpenNews,
    setOpenUsers,
  } = useDashboardLayout();

  const theme = useTheme();
  const isMobile = useMediaQuery(theme.breakpoints.down("sm"));

  return (
    <>
      <HeaderComponent>
        <Box className="flex items-center gap-4">
          <SidebarIconComponent
            homePage={false}
            open={open}
            handleToggleMenu={handleToggleMenu}
          />
          <h1 className="text-[var(--primary)] text-xl font-bold">Sua Logo</h1>
        </Box>
        <UserMenuBoxComponent
          handleToggleUserMenu={handleToggleUserMenu}
          avatar={user?.avatar}
          email={user?.email}
          name={user?.name}
        />

        <UserMenuComponent
          anchorEl={anchorEl}
          handleClose={handleClose}
          openUserMenu={openUserMenu}
          handleLogout={handleLogout}
        />
      </HeaderComponent>

      <SidebarComponent
        open={open}
        setOpen={setOpen}
        openBanners={openBanners}
        openNews={openNews}
        openUsers={openUsers}
        setOpenBanners={setOpenBanners}
        setOpenNews={setOpenNews}
        setOpenUsers={setOpenUsers}
        user={user}
      />

      <main
        className={`min-h-screen w-full flex transition-all duration-300 bg-[var(--black)] justify-center items-start`}
        style={{
          marginLeft: useSidebar
            ? isMobile
              ? "0"
              : open
              ? "250px"
              : "60px"
            : "0",
          width: useSidebar
            ? `calc(100% - ${isMobile ? "0px" : open ? "250px" : "60px"})`
            : "100%",
        }}
      >
        {children}
      </main>
    </>
  );
};

export default DashboardLayout;
