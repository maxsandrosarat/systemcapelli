INSERT INTO `admins` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `ativo`, `created_at`, `updated_at`) VALUES
	(1, 'Max Admin', 'msaratribeiro@gmail.com', NULL, '$2y$10$bNS3OqxkEfCtwlYYeXOU0uyuO905TAt6pgSQn.z8.B7iAq5tLD2gS', NULL, 1, '2020-03-31 00:23:41', '2020-03-31 00:23:41'),
	(2, 'Jhenifer G.', 'jheniferg@gmail.com', NULL, '$2y$10$u7hzB6.F5wgAd1INs8BknuxlSW5AvBLukhCZJ0l3.PRix29ynTd8S', NULL, 1, '2021-08-27 14:54:29', '2021-08-27 14:54:29');

INSERT INTO `banners` (`id`, `ordem`, `titulo`, `descricao`, `ativo`, `foto`, `created_at`, `updated_at`) VALUES
	(1, 2, 'Tartaruga', '<a type="button"  target="_blank" href="https://music.amazon.com.br/" class="badge bg-dark" data-toggle="tooltip" data-placement="right" title="Site">Ir para o site</a>', 1, 'banners/4DgAgPPAwx8EhEjvKMan2BpbkxfR5AzIVgb3tZCB.jpg', '2021-09-02 09:14:45', '2021-09-02 13:07:45'),
	(2, 3, 'Cachorrinhos', 'Pet', 1, 'banners/qTJlk2TnWMHyWU2fyXcNSoIcULblBbnA4DSerbv9.jpg', '2021-09-02 10:16:20', '2021-09-02 13:07:45'),
	(3, 1, 'Gatinho', '<button type="button" class="btn btn-primary">\r\n  Notifications <span class="badge bg-secondary">4</span>\r\n</button>', 1, 'banners/ZUtKCoULKBA3b0eo3X1S9wipVdL8KIzFX58E4H0i.jpg', '2021-09-02 11:56:29', '2021-09-02 13:07:45');

INSERT INTO `configs` (`id`, `diaSemana`, `abertura`, `intervaloInicio`, `intervaloFim`, `fechamento`, `created_at`, `updated_at`) VALUES
	(1, 'Domingo', '00:00:00', '00:00:00', '00:00:00', '00:00:00', '2021-08-26 15:36:20', '2021-08-26 15:36:20'),
	(2, 'Segunda-Feira', '08:00:00', '12:00:00', '12:59:00', '18:00:00', '2021-08-26 15:36:20', '2021-08-27 11:11:45'),
	(3, 'Terça-Feira', '08:00:00', '12:00:00', '12:59:00', '18:00:00', '2021-08-26 15:36:20', '2021-08-27 11:11:37'),
	(4, 'Quarta-Feira', '08:00:00', '12:00:00', '12:59:00', '18:00:00', '2021-08-26 15:36:20', '2021-08-27 11:11:37'),
	(5, 'Quinta-Feira', '08:00:00', '12:00:00', '12:59:00', '18:00:00', '2021-08-26 15:36:20', '2021-08-27 11:11:37'),
	(6, 'Sexta-Feira', '08:00:00', '12:00:00', '12:59:00', '18:00:00', '2021-08-26 15:36:20', '2021-08-27 11:11:37'),
	(7, 'Sábado', '08:00:00', '00:00:00', '00:00:00', '11:59:00', '2021-08-26 15:36:20', '2021-08-30 15:30:57');

INSERT INTO `funcaos` (`id`, `nome`, `created_at`, `updated_at`) VALUES
	(1, 'Cabelereiro(a)', '2021-08-25 22:18:17', '2021-08-27 10:09:12'),
	(2, 'Manicure', '2021-08-25 22:18:27', '2021-08-25 22:18:27'),
	(3, 'Pedicure', '2021-08-25 22:18:41', '2021-08-25 22:18:41'),
	(4, 'Manicure & Pedicure', '2021-08-27 10:09:05', '2021-08-27 10:09:05');

INSERT INTO `funcs` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `ativo`, `created_at`, `updated_at`) VALUES
	(1, 'MAXSANDRO SARAT RIBEIRO', 'msaratribeiro@gmail.com', NULL, '$2y$10$Tznhp8csYuQ.iCKBZbrCCeMBoiN0fodDZrHLGU96Id0rPfT0nza4W', NULL, 1, '2021-08-25 22:19:07', '2021-08-25 22:19:07'),
	(2, 'Jhenifer', 'jenifer@email.com', NULL, '$2y$10$rPWhqnrJjGvzGxSkltubA.LZfRyfKBKTkUibxsKHBK5ohq0LxbGcW', NULL, 1, '2021-08-25 22:19:36', '2021-08-25 22:19:36'),
	(3, 'Lucas', 'lucas@email.com', NULL, '$2y$10$blYviso.J4bbR2Wrcni0He.2XIFvtoAsXwNNtnyJN/noVeTN3f/o.', NULL, 1, '2021-08-27 10:10:01', '2021-08-27 10:10:01');

INSERT INTO `func_funcaos` (`func_id`, `funcao_id`, `created_at`, `updated_at`) VALUES
	(1, 1, '2021-08-25 22:19:07', '2021-08-25 22:19:07'),
	(2, 2, '2021-08-27 10:09:24', '2021-08-27 10:09:24'),
	(2, 3, '2021-08-27 10:09:24', '2021-08-27 10:09:24'),
	(2, 4, '2021-08-27 10:09:24', '2021-08-27 10:09:24'),
	(3, 1, '2021-08-27 10:10:01', '2021-08-27 10:10:01'),
	(3, 2, '2021-08-27 10:10:01', '2021-08-27 10:10:01'),
	(3, 3, '2021-08-27 10:10:01', '2021-08-27 10:10:01'),
	(3, 4, '2021-08-27 10:10:01', '2021-08-27 10:10:01');

INSERT INTO `servicos` (`id`, `nome`, `preco`, `tempo`, `funcao_id`, `ativo`, `promocao`, `preco_antigo`, `inicio`, `fim`, `ressalva`, `created_at`, `updated_at`) VALUES
	(1, 'Pé & Mão', 50.00, '01:00:00', 3, 1, 0, NULL, NULL, NULL, NULL, '2021-08-25 22:27:26', '2021-09-02 11:54:30'),
	(2, 'Corte Chanel Curto', 70.00, '01:00:00', 1, 1, 0, NULL, NULL, NULL, NULL, '2021-08-27 10:10:26', '2021-09-02 11:52:50');

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `ativo`, `nascimento`, `telefone`, `whatsapp`, `facebook`, `instagram`, `created_at`, `updated_at`) VALUES
	(1, 'Maxsandro S. R.', 'msaratribeiro@gmail.com', NULL, '$2y$10$cwUFfd8.cg1cDT/DBZs0RuUhvt1Ecgh5RVkzGV0THsbElxslEx7tS', NULL, 1, NULL, '(67) 99146-2832', NULL, NULL, NULL, '2021-08-27 10:11:59', '2021-09-02 13:18:01'),
	(2, 'Jhenifer G.', 'jhenifer@gmail.com', NULL, '$2y$10$re2CrntkoT.Mhk8dlyzueuV.IR8.xaRyOXC1E4cf1rK7sjw6fqlRG', NULL, 1, '2021-08-27', '(67) 99146-2832', NULL, NULL, NULL, '2021-08-27 13:17:54', '2021-09-02 13:17:03'),
	(3, 'Lucas G.', 'lucas@email.com', NULL, '$2y$10$QqgTcgdErUDhzYVlwdD/LOuB1Sv3EFc/zEDyV1SG5wy1sR/9ZPZOm', NULL, 1, NULL, '(67) 99146-2832', NULL, NULL, NULL, '2021-08-27 14:28:18', '2021-09-02 13:28:29'),
	(4, 'Raphael T.', 'rafael@email.com', NULL, '$2y$10$l7/FJP.v2Zq5PjiIpfaGvOnz2qiUUC0uOOaDA9rzIbBlrtJ6N/qDy', NULL, 1, NULL, '67991462832', NULL, NULL, NULL, '2021-08-27 14:35:55', '2021-08-27 14:35:55');
