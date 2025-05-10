return {
    { import = "lazyvim.plugins.extras.lsp.none-ls" }, -- 👈 activa soporte none-ls

    {
        "nvimtools/none-ls.nvim",
        opts = function(_, opts)
            local null_ls = require("null-ls")
            opts.sources = opts.sources or {}
            table.insert(
                opts.sources,
                null_ls.builtins.formatting.pint.with({
                    filetypes = { "php" },
                })
            )
        end,
    },
}
