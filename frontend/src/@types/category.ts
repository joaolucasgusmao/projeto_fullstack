export interface ICategoryReturn {
  id: number;
  name: string;
  slug: string;
  created_at: string;
}

export type ICreateCategory = Omit<ICategoryReturn, "id" | "created_at">;
