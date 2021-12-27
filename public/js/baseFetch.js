function baseFetch(url, options) {
  return fetch(url, options)
    .then((res) => {
      if (res.ok) {
        return res.json();
      }
      throw new Error("Erro ao fazer a requisição");
    })
    .catch((err) => {
      alert("Não foi possivel buscar os produtos: " + err.message);
      // throw err;
    });
}
