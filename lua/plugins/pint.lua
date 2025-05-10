return {
    "nvimtools/none-ls.nvim",
    opts = function(_, opts)
        local null_ls = require("null-ls")
        opts.sources = opts.sources or {}
        table.insert(opts.sources, null_ls.builtins.formatting.pint)
    end,
}
