import React, { useState, useEffect } from "react";

interface CheckboxProps {
  label?: string;
  value: string[];
  onChange: (value: string[]) => void;
  options: { id: string; name: string }[];
}

const Checkbox: React.FC<CheckboxProps> = ({
  label,
  value,
  onChange,
  options,
}) => {
  const [selected, setSelected] = useState<string[]>(value);

  useEffect(() => {
    setSelected(value);
  }, [value]);

  const handleChange = (id: string) => {
    const newValue = selected.includes(id)
      ? selected.filter((v) => v !== id)
      : [...selected, id];

    setSelected(newValue);
    onChange(newValue);
  };

  return (
    <>
      <label className="text-[var(--gray)] text-base block mb-2">{label}</label>
      <div className="flex flex-wrap gap-5">
        {options.map((option) => {
          const isChecked = selected.includes(option.id);
          return (
            <label
              key={option.id}
              className="flex items-center hover:scale-105 gap-2 p-2 text-base rounded-md transition-all duration-300 bg-[var(--black-3)] text-[var(--gray)]"
            >
              <input
                type="checkbox"
                checked={isChecked}
                onChange={() => handleChange(option.id)}
                className="w-5 h-5 accent-[var(--primary)] transition-all duration-300 cursor-pointer"
              />
              <span>{option.name}</span>
            </label>
          );
        })}
      </div>
    </>
  );
};

export default Checkbox;
