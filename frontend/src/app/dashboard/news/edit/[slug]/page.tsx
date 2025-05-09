"use client";

import { useEffect, useState } from "react";
import { useRouter } from "next/navigation";
import useAuth from "@/hooks/useAuth";
import PropagateLoader from "react-spinners/PropagateLoader";
import { DashboardLayoutProvider } from "@/context/DashboardLayoutContext";
import DashboardLayout from "@/components/Dashboard/Layout/DashboardLayout";
import { ToastContainer } from "react-toastify";
import EditNewsComponentForm from "@/components/Dashboard/News/Forms/EditNewsComponentForm";
import { INewsReturn } from "@/@types/news";
import useGetCategories from "@/hooks/useGetCategories";

interface EditNewsPageProps {
  params: Promise<{ slug: string }>;
}

const EditNewsPage = ({ params }: EditNewsPageProps) => {
  const [slug, setSlug] = useState<string | null>(null);
  const [news, setNews] = useState<INewsReturn | null>(null);
  const [loading, setLoading] = useState(true);

  const isAuthenticated = useAuth();
  const router = useRouter();

  useEffect(() => {
    const fetchParams = async () => {
      const resolvedParams = await params;
      setSlug(resolvedParams.slug);
    };

    fetchParams();
  }, [params]);

  const { categories, loading: categoriesLoading, error } = useGetCategories();

  useEffect(() => {
    if (isAuthenticated === false) {
      router.push("/login");
    }
  }, [isAuthenticated, router]);

  useEffect(() => {
    if (!isAuthenticated) return;

    const fetchNews = async () => {
      try {
        const newsResponse = await fetch(`/api/news/${slug}`);

        if (newsResponse.ok) {
          const newsData: INewsReturn = await newsResponse.json();
          setNews(newsData);
        } else {
          throw new Error("Erro ao buscar notícia");
        }
      } catch (error) {
        console.error("Erro ao buscar notícia:", error);
      } finally {
        setLoading(false);
      }
    };

    fetchNews();
  }, [isAuthenticated, slug]);

  if (
    loading ||
    categoriesLoading ||
    isAuthenticated === null ||
    isAuthenticated === false
  ) {
    return (
      <div className="min-h-screen flex items-center justify-center bg-[var(--black)]">
        <PropagateLoader color="var(--primary)" />
      </div>
    );
  }

  if (error) {
    return (
      <div className="min-h-screen flex items-center justify-center bg-[var(--black)]">
        <span className="text-red-500">{error}</span>
      </div>
    );
  }

  return (
    <DashboardLayoutProvider>
      <DashboardLayout useSidebar={true}>
        <ToastContainer position="top-right" autoClose={1000} theme="dark" />
        {news && categories && (
          <EditNewsComponentForm news={news} categories={categories} />
        )}
      </DashboardLayout>
    </DashboardLayoutProvider>
  );
};

export default EditNewsPage;
